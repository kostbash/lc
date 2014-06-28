<?php
// Класс отвечающий за работу генератора на основе списка слов
class ListOfWords
{
    private $model; // модель генератора
    private $template;
    private $attributes = array();
    
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
            
            $this->template->id_visual = $this->getVisual();
            
            if($this->template->save())
            {
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
            $this->attributes['GeneratorsTemplates']['type_of_building']
        )
        {
            return true;
        }
        return false;
    }
    
    function getVisual() {
        switch($this->template->type_of_building)
        {
            case  1: return 1;
            case  2: return 1;
            case  3: return 1;
            case  4: return 3;
            case  5: return 3;
            case  6: return 3;
            case  7: return 3;
            case  8: return 6;
            case  9: return 6;
            case 10: return 6;
            default : return Generators::DEFAULT_VISUAL;
        }
    }
    
    function generateExercises()
    {
        $count=0; // количество успешных генераций
        $attempts = 0; // попытки сгенировать
        $exercises = array();
        $answers = array();
        while(($count < $this->numberExericises()) && $attempts < 1000)
        {
            $word = $this->template->Words[$count];
            
            $attributes = $this->exercisesAttributes($word);
            $exerciseModel = new Exercises;
            $exerciseModel->condition = $attributes['condition'];
            $exerciseModel->number = $count;
            $exercises[$count] = $exerciseModel;

            foreach($attributes['answers'] as $index => $answer)
            {
                $answers[$count][$index] = $answer;
            }
            
            $count++;
            $attempts++;
        }
        
        return array(
            'count'=>$count,
            'attempts'=>$attempts,
            'exercises'=>$exercises,
            'answers'=>$answers,
        );
    }
    
    function exercisesAttributes(GeneratorsWords $word)
    {
        $result = array();
        switch($this->template->type_of_building)
        {
            case 1: 
                $result['condition'] = "Напишите слово, соответсвующее картинке <br /><img src='$word->image' alt='' />";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1);
                break;
            case 2: 
                $result['condition'] = "Напишите слово, перевод которого - $word->translate";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1);
                break;
            case 3: 
                $result['condition'] = "Напишите перевод слова - $word->word";
                $result['answers'][] = array('answer'=>$word->translate, 'is_right'=>1);
                break;
            case  4: 
                $result['condition'] = "Выберите слово, соответсвующее картинке <br /><img src='$word->image' alt='' />";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1);
                break;
            case  5: 
                $result['condition'] = "Выберите слово, перевод которого - $word->translate";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1);
                break;
            case  6: 
                $result['condition'] = "Выберите перевод слова - $word->word";
                $result['answers'][] = array('answer'=>$word->translate, 'is_right'=>1);
                break;
            case  7: 
                $result['condition'] = "Выберите тег слова - $word->word";
                $result['answers'][] = array('answer'=>$word->translate, 'is_right'=>1);
                break;
            case  8: return 6;
            case  9: return 6;
            case 10: return 6;
            default : return Generators::DEFAULT_VISUAL;
        }
        return $result;
    }
    
    function numberExericises()
    {
        if($this->template->type_of_building == 10)
        {
            return $this->template->number_exercises;
        } else {
            return GeneratorsTemplatesSelectedWords::model()->countByAttributes(array('id_template'=>$this->template->id));
        }
    }
}