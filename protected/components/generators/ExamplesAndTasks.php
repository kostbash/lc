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

            if($this->template->separate_template_and_correct_answers)
            {
                if(!$this->template->correct_answers)
                {
                    return false;
                }
            }
            else
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
                // в шаблоне нам не нужно заменять =, на ==. Поэтому делаем отдельный массив
                $forReplaceWithPhpConditions = $forReplace;
                $forReplaceWithPhpConditions['patterns'][] = '#=#u'; // заменяем одинарное равно на двойное
                $forReplaceWithPhpConditions['replacements'][] = '==';
                $forReplaceWithPhpConditions['patterns'][] = '#>==#u';
                $forReplaceWithPhpConditions['replacements'][] = '>=';
                $forReplaceWithPhpConditions['patterns'][] = '#<==#u';
                $forReplaceWithPhpConditions['replacements'][] = '<=';
                $forReplaceWithPhpConditions['patterns'][] = '#mod#u'; // остаток от деления заменяем на php-ный
                $forReplaceWithPhpConditions['replacements'][] = '%';
                $convertedTemplate = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $this->template->template);
                $convertedTemplate = preg_replace_callback("#\{(.*)\}#uUm", "Generators::callBackForBraces", $convertedTemplate); // выполняем выражения в {}
                $convertedTemplate = preg_replace_callback("#\[(.*)\]#uUm", "Generators::callBackForSquareBrackets", $convertedTemplate); // выполняем выражения в []
                
                $convertedConditions = Generators::getConvertStrings($forReplaceWithPhpConditions['patterns'], $forReplaceWithPhpConditions['replacements'], $this->template->conditionsArray);
                $wrongAnswers = $this->template->WrongAnswersArray;
                if(GeneratorsTemplates::ConditionsMet($convertedConditions))
                {
                    $exerciseModel = new Exercises;
                    $exerciseModel->condition = $convertedTemplate;
                    $exerciseModel->number = $count;
                    $exercises[$count] = $exerciseModel;

                    // получием список неправильных ответов задания
                    if(!empty($wrongAnswers))
                    {
                        if($this->template->separate_template_and_correct_answers)
                        {
                            $convertedCorrectAnswers = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $this->template->correct_answers);
                            $convertedCorrectAnswers = preg_replace_callback("#\{(.*)\}#uUm", "Generators::callBackForBraces", $convertedCorrectAnswers); // выполняем выражения в {}
                            $convertedCorrectAnswers = preg_replace_callback("#\[(.*)\]#uUm", "Generators::callBackForSquareBrackets", $convertedCorrectAnswers); // выполняем выражения в []
                            
                            $convertedWrongAnswers = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $wrongAnswers);
                            foreach($convertedWrongAnswers as $index => $convertedWrongAnswer)
                            {
                                $convertedWrongAnswer = preg_replace_callback("#\{(.*)\}#uUm", "Generators::callBackForBraces", $convertedWrongAnswer); // выполняем выражения в {}
                                $answers[$count][$index]['answer'] = preg_replace_callback("#\[(.*)\]#uUm", "Generators::callBackForSquareBrackets", $convertedWrongAnswer); // выполняем выражения в []
                            }
                        }
                        else
                        {
                            $convertedCorrectAnswers = Generators::getConvertStrings($forReplaceWithPhpConditions['patterns'], $forReplaceWithPhpConditions['replacements'], $this->template->correct_answers);
                            $convertedCorrectAnswers = Generators::executeCode($convertedCorrectAnswers);
                            $convertedWrongAnswers = Generators::getConvertStrings($forReplaceWithPhpConditions['patterns'], $forReplaceWithPhpConditions['replacements'], $wrongAnswers);
                            foreach($convertedWrongAnswers as $index => $convertedWrongAnswer)
                            {
                                $answers[$count][$index]['answer'] = Generators::executeCode($convertedWrongAnswer);
                            }
                        }
                    }

                    // сохраняем правильный ответ
                    $index++;
                    $answers[$count][$index]['answer'] = $convertedCorrectAnswers;
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