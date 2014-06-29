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
            
            if($this->template->id_visual != 6) // сопоставление
            {
                $this->template->number_exercises = GeneratorsTemplatesSelectedWords::model()->countByAttributes(array('id_template'=>$this->template->id));
            }
            
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
        $comparisons = array(); // сопоставления
        while(($count < $this->template->number_exercises) && $attempts < 1000)
        {
            if($this->template->id_visual!=6)
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
            
            $comparisons[$count] = $attributes['comparisons'];
            
            $count++;
            $attempts++;
        }
        
        return array(
            'count'=>$count,
            'attempts'=>$attempts,
            'exercises'=>$exercises,
            'answers'=>$answers,
            'comparisons'=>$comparisons,
        );
    }
    
    function exercisesAttributes(GeneratorsWords $word=null)
    {
        $result = array();
        switch($this->template->type_of_building)
        {
            case 1: // Точный ответ: картинка-слово
                $result['condition'] = "Напишите слово, соответсвующее картинке <br /><img src='/".Yii::app()->params['WordsImagesPath']."/".$word->image."' alt='' />";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1); // добавляем правильный ответ
                break;
            case 2: // Точный ответ: перевод-слово
                $result['condition'] = "Напишите слово, перевод которого - $word->translate";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1); // добавляем правильный ответ
                break;
            case 3: // Точный ответ: слово-перевод
                $result['condition'] = "Напишите перевод слова - $word->word";
                $result['answers'][] = array('answer'=>$word->translate, 'is_right'=>1); // добавляем правильный ответ
                break;
            case  4: // Выбор из списка: картинка-слово
                $result['condition'] = "Выберите слово, соответсвующее картинке <br /><img src='/".Yii::app()->params['WordsImagesPath']."/".$word->image."' alt='' />";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1); // добавляем правильный ответ
                // добавляем неправильные ответы
                foreach($this->anotherWordsWithHadTags($word) as $anotherWord)
                {
                    $result['answers'][] = array('answer'=>$anotherWord->word);
                }
                break;
            case  5: // Выбор из списка: перевод-слово
                $result['condition'] = "Выберите слово, перевод которого - $word->translate";
                $result['answers'][] = array('answer'=>$word->word, 'is_right'=>1); // добавляем правильный ответ
                // добавляем неправильные ответы
                foreach($this->anotherWordsWithHadTags($word) as $anotherWord)
                {
                    $result['answers'][] = array('answer'=>$anotherWord->word);
                }
                break;
            case  6: // Выбор из списка: слово-перевод
                $result['condition'] = "Выберите перевод слова - $word->word";
                $result['answers'][] = array('answer'=>$word->translate, 'is_right'=>1); // добавляем правильный ответ
                // добавляем неправильные ответы
                foreach($this->anotherWordsWithHadTags($word) as $anotherWord)
                {
                    $result['answers'][] = array('answer'=>$anotherWord->translate);
                }
                break;
            case  7: // Выбор из списка: слово-тег
                $result['condition'] = "Выберите тег слова - $word->word";
                $tagIndex = array_rand($word->Tags);
                $tag = $tagIndex >=0 ? $word->Tags[$tagIndex] : false;
                if($tag)
                {
                    $result['answers'][] = array('answer'=>$tag->name, 'is_right'=>1); // добавляем правильный ответ
                }
                // добавляем неправильные ответы
                foreach($this->anotherTags($word) as $anotherTag)
                {
                    $result['answers'][] = array('answer'=>$anotherTag->name);
                }
                break;
            case  8: // Сопоставление: картинка-слово
                $wordsIndexes = array_rand($this->template->Words, $this->template->number_words);
                $index = 0;
                $result['condition'] = "Поставьте соответствие между словами и картинками";
                
                // добавляем все ответы и сопостравления
                foreach($wordsIndexes as $wordIndex)
                {
                    $word = $this->template->Words[$wordIndex];
                    $result['answers'][$index++] = array('answer'=>"<img src='/".Yii::app()->params['WordsImagesPath']."/".$word->image."' alt='' />", 'is_right'=>1);
                    $result['answers'][$index++] = array('answer'=>$word->word, 'is_right'=>1);
                    $result['comparisons'][] = array('answer_one'=>$index-2, 'answer_two'=>$index-1);
                }
                break;
            case  9: // Сопоставление: перевод-слово
                $wordsIndexes = array_rand($this->template->Words, $this->template->number_words);
                $index = 0;
                $result['condition'] = "Поставьте соответствие между словами и их переводом";
                
                // добавляем все ответы и сопостравления
                foreach($wordsIndexes as $wordIndex)
                {
                    $word = $this->template->Words[$wordIndex];
                    $result['answers'][$index++] = array('answer'=>$word->translate, 'is_right'=>1);
                    $result['answers'][$index++] = array('answer'=>$word->word, 'is_right'=>1);
                    $result['comparisons'][] = array('answer_one'=>$index-2, 'answer_two'=>$index-1);
                }
                break;
            case 10: // Сопоставление: слово-тег
                $wordsIndexes = array_rand($this->template->Words, $this->template->number_words);
                $index = 0;
                $result['condition'] = "Поставьте соответствие между словами и их тегами";
                
                // добавляем все ответы и сопостравления
                foreach($wordsIndexes as $wordIndex)
                {
                    $word = $this->template->Words[$wordIndex];
                    $tagIndex = array_rand($word->Tags);
                    $tagName = $tagIndex >=0 ? $word->Tags[$tagIndex]->name : '';
                    $result['answers'][$index++] = array('answer'=>$word->word, 'is_right'=>1);
                    $result['answers'][$index++] = array('answer'=>$tagName, 'is_right'=>1);
                    $result['comparisons'][] = array('answer_one'=>$index-2, 'answer_two'=>$index-1);
                }
                break;
        }
        return $result;
    }
    
    function anotherWordsWithHadTags(GeneratorsWords $word) {
        $criteria = new CDbCriteria();
        $criteria->with = array('Tags', 'SelectedWords');
        $criteria->together = true;
        $criteria->addInCondition('Tags.id', $word->ExistIdsTags, 'AND');
        $criteria->addCondition('t.id<>:id_word', 'AND');
        $criteria->params['id_word'] = $word->id;
        $criteria->compare('SelectedWords.id_template', $this->template->id);
        $criteria->order = 'RAND()';
        $criteria->limit = 5;
        return GeneratorsWords::model()->findAll($criteria);
    }
    
    function anotherTags(GeneratorsWords $word) {
        $criteria = new CDbCriteria();
        $criteria->addNotInCondition('id', $word->ExistIdsTags, 'AND');
        $criteria->order = 'RAND()';
        $criteria->limit = 5;
        return GeneratorsTags::model()->findAll($criteria);
    }
}