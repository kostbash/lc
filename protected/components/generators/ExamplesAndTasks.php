<?php
// Класс отвечающий за работу генератора примеров и задач
class ExampleAndTasks
{
    private $model; // модель генератора
    private $template;
    private $attributes = array();
    private $useReplacements = array();
    
    function __construct($model, $attributes)
    {
        $this->model = $model;
        $this->template = $model->Template;
        $this->attributes = $attributes;
    }
    
    function saveSettings()
    {
        if($this->haveNeedSettings())
        {
            $this->template->attributes = $this->attributes['GeneratorsTemplates'];

            if(!$this->template->separate_template_and_correct_answers)
            {
                $this->template->correct_answers = "";
            } elseif(!$this->template->correct_answers)
            {
                $this->template->correct_answers = $this->template->template;
            }
            if(!$this->template->id_visual)
            {
                $this->template->id_visual = Generators::DEFAULT_VISUAL;
            }
            
            if($this->template->save())
            {
                $this->model->saveVariables($this->attributes['GeneratorsTemplatesVariables']);
                $this->model->saveConditions($this->attributes['GeneratorsTemplatesConditions']);
                $this->model->saveWrongAnswers($this->attributes['WrongAnswers']);
                return true;
            }
        }
        return false;
    }
    
    function haveNeedSettings()
    {
        if
        (
            $this->attributes &&
            $this->attributes['GeneratorsTemplates'] &&
            $this->attributes['GeneratorsTemplates']['template'] &&
            $this->attributes['GeneratorsTemplates']['number_exercises']
        )
        {
            return true;
        }
        return false;
    }
    
    function generateExercises()
    {
        $count=0; // количество успешных генераций
        $attempts = 0; // попытки сгенировать
        $exercises = array();
        $answers = array();
        while(($count < $this->template->number_exercises) && $attempts < 1000)
        {
            $forReplace = $this->template->ForPeplace;
            if($this->notExistsReplacements($forReplace['replacements']))
            {
                $convertedTemplate = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $this->template->template);
                $convertedCorrectAnswers = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $this->template->correct_answers);
                $convertedConditions = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $this->template->conditionsArray);
                $convertedWrongAnswers = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $this->template->WrongAnswersArray);
                if(GeneratorsTemplates::ConditionsMet($convertedConditions))
                {
                    $exerciseModel = new Exercises;
                    $exerciseModel->condition = $convertedTemplate;
                    $exerciseModel->number = $count;
                    $exercises[$count] = $exerciseModel;

                    // получием список неправильных ответов задания
                    if(!empty($convertedWrongAnswers))
                    {
                        foreach($convertedWrongAnswers as $index => $convertedWrongAnswer)
                        {
                            $answers[$count][$index]['answer'] = Generators::executeCode($convertedWrongAnswer);
                        }
                    }

                    // сохраняем правильный ответ
                    $index++;
                    $answers[$count][$index]['answer'] = Generators::executeCode($convertedCorrectAnswers);
                    $answers[$count][$index]['is_right'] = 1;
                    unset($index);
                    $count++;
                    $this->useReplacements[] = $forReplace['replacements'];
                }
            }
            $attempts++;
        }
        
        return array(
            'count'=>$count,
            'attempts'=>$attempts,
            'exercises'=>$exercises,
            'answers'=>$answers,
        );
    }
    
    // проверяем существуют ли такие значения переменных, тем самым исключаем одиннаковые задания
    function notExistsReplacements(array $replacements)
    {
        foreach($this->useReplacements as $useReps)
        {
            sort($useReps);
            sort($replacements);
            if($useReps==$replacements)
            {
                return false;
            }
        }
        return true;
    }
}