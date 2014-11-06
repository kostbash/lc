<?php

/**
 * This is the model class for table "oed_exercises".
 *
 * The followings are the available columns in table 'oed_exercises':
 * @property integer $id
 * @property string $condition
 * @property integer $difficulty
 */
class Exercises extends CActiveRecord
{

        public $difficultyMass = array(0,1,2,3,4,5,6,7,8,9,10);
        public static $defaultDifficulty = 5;
        public $SkillsIds;
        public $limit;
        public static $defaultType = 1;
        public $number;
        public $pageSize = 10;
        public static $pageSizes = array(
            '5' => '5',
            '10' => '10',
            '25' => '25',
            '50' => '50',
            '100' => '100'
        );
    
	public function tableName()
	{
		return 'oed_exercises';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('condition, id_type, course_creator_id, change_date', 'required'),
                        array('change_date', 'date', 'format'=>'yyyy-mm-dd hh:mm:ss'),
			array('difficulty, limit, id_type, id_map, id_visual, course_creator_id', 'numerical', 'integerOnly'=>true),
			array('id, condition, limit, SkillsIds, difficulty, pageSize', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Skills' => array(self::MANY_MANY, 'Skills', 'oed_exercise_and_skills(id_exercise, id_skill)'),
                    'ExerciseAndSkills'=>array(self::HAS_MANY, 'ExerciseAndSkills', 'id_exercise'),
                    'ExercisesGroup' => array(self::MANY_MANY, 'GroupOfExercises', 'oed_group_and_exercises(id_exercise, id_group)'),
                    'Answers'=>array(self::HAS_MANY, 'ExercisesListOfAnswers', 'id_exercise', 'order'=>'Answers.id ASC'),
                    'Type'=>array(self::BELONGS_TO, 'ExercisesTypes', 'id_type'),
                    'Visual'=>array(self::BELONGS_TO, 'ExercisesVisuals', 'id_visual'),
                    'Comparisons'=>array(self::HAS_MANY, 'ExercisesComparisons', 'id_exercise'),
                    'Questions'=>array(self::HAS_MANY, 'ExercisesQuestions', 'id_exercise'),
                    'Map'=>array(self::BELONGS_TO, 'Maps', 'id_map'),
                    'Bags'=>array(self::HAS_MANY, 'ExercisesBags', 'id_exercise'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'condition' => 'Условие',
			'correct_answers' => 'Правильный ответ',
			'difficulty' => 'Сложность',
                        'limit' => 'Число заданий',
                        'pageSize'=>'Кол-во выводимых заданий',
                        'id_type'=>'Тип',
                        'id_visual'=>'Визуализация',
                        'skillsText' => 'Умения',
		);
	}

        // возвращает id блоков в которых используеться задание
        public function getIdsBlocks()
        {
            $queryGroups = "SELECT id_group FROM `oed_group_and_exercises` WHERE `id_exercise`=$this->id";
            $queryParts = "SELECT p.id_group FROM `oed_parts_of_test_and_exercises` pe, `oed_parts_of_test` p  WHERE pe.id_part=p.id AND pe.id_exercise=$this->id";
            $groups = Yii::app()->db->createCommand($queryGroups)->queryAll();
            $parts = Yii::app()->db->createCommand($queryParts)->queryAll();
            $result = array();
            foreach($groups as $group)
            {
                $result[] = $group['id_group'];
            }
            foreach($parts as $part)
            {
                $result[] = $part['id_group'];
            }
            return $result;
        }
        
	public function search()
	{
            $criteria=new CDbCriteria;
            $params = array();
            $course_id = $this->course_creator_id ? $this->course_creator_id : 0;
            $criteria->compare('condition',$this->condition,true);
            $criteria->compare('difficulty',$this->difficulty);
            $criteria->compare('id_type', $this->id_type);
            $criteria->compare('id_visual', $this->id_visual);
            $criteria->compare('course_creator_id', $course_id);
            if($this->SkillsIds) {
                $criteria->addCondition("EXISTS (SELECT * FROM `oed_exercise_and_skills` s WHERE s.id_exercise = t.id AND s.id_skill IN ('".implode("','", $this->SkillsIds)."'))");
                $criteria->addCondition("((SELECT COUNT(*) FROM `oed_exercise_and_skills` s WHERE s.id_exercise = t.id AND s.id_skill IN ('".implode("','", $this->SkillsIds)."'))>='".count($this->SkillsIds)."')");
            }
            $params['pagination'] = false;
            
            if($this->pageSize!=0)
                $params['pagination']['pageSize'] = $this->pageSize;
            else
                $params['pagination'] = false;
            
            $params['criteria']=$criteria;
            return new CActiveDataProvider($this, $params);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getDataDifficulty() {
            $model = new Exercises;
            $mass = array();
            foreach($model->difficultyMass as $dificult) {
                $mass[$dificult] = $dificult;
            }
            return $mass;
        }
        
        public function canSaveFromLesson($id_lesson) {
            if($this->lesson_creator_id != $id_lesson)
                return;
            $exercisesGroups = GroupAndExercises::model()->findAllByAttributes(array('id_exercise'=>$this->id));
            if(count($exercisesGroups) > 1)
            {
                foreach($exercisesGroups as $exercisesGroup)
                {
                    if($exercisesGroup->Lesson->id !== $id_lesson)
                        return;
                }
            }
            return true;
        }
        
        public function canSaveFromGroup($id_group) {
            $group = GroupOfExercises::model()->findByPk($id_group);
            if($this->course_creator_id != $group->id_course)
                return;
            return true;
        }
        
        public function getIdsUsedSkills() {
            $ids = array();
            foreach($this->Skills as $skill)
            {
                $ids[] = $skill->id;
            }
            return $ids;
        }
        
        public function afterDelete() {
            ExercisesListOfAnswers::model()->deleteAllByAttributes(array('id_exercise'=>$this->id));
            ExerciseAndSkills::model()->deleteAllByAttributes(array('id_exercise'=>$this->id));
            UserAndExercises::model()->deleteAllByAttributes(array('id_exercise'=>$this->id));
            ExercisesComparisons::model()->deleteAllByAttributes(array('id_exercise'=>$this->id));
            ExercisesQuestions::model()->deleteAllByAttributes(array('id_exercise'=>$this->id));
            parent::afterDelete();
        }
        
        public function getCanDelete() {
            if(!$this->canChange or $this->ExercisesGroup)
                return 0;
            return 1;
        }
        
        public function getCanChange()
        {
            $res = true;
            if(!Yii::app()->user->checkAccess('admin'))
            {
                if(!Courses::existCourseById($this->course_creator_id))
                    $res = false;
            }
            return $res;
        }
        
        public static function isRightAnswer($id_exercise, $answers) {
            $exercise = Exercises::model()->findByPk($id_exercise);
            if($exercise)
            {
                $rightAnswers = $exercise->rightAnswers;
                if($rightAnswers)
                {
                    if($exercise->id_visual==1) // точный ответ
                    {
                        foreach($rightAnswers as $rightAnswer)
                        {
                            if($rightAnswer->reg_exp)
                            {
                                if(@preg_match($rightAnswer->answer, $answers))
                                {
                                    return true;
                                }
                            } else {
                                if($rightAnswer->answer == $answers)
                                {
                                    return true;
                                }
                            }
                        }
                    }
                    elseif($exercise->id_type==2) // выбор одного из списка
                    {
                        if($rightAnswers[0]->id == $answers)
                        {
                            return true;
                        }
                    }
                    elseif($exercise->id_type==3) // выбор нескольких
                    {
                        $answers = is_array($answers) ? $answers : array(trim($answers));
                        if(count($rightAnswers) == count($answers))
                        {
                            $idsRight = array();
                            foreach($rightAnswers as $rightAnswer)
                            {
                                $idsRight[] = $rightAnswer->id;
                            }
                            
                            if(!array_diff($idsRight, $answers))  // если есть все правильные ответы
                            {
                                return true;
                            }
                        }
                    }
                    elseif($exercise->id_visual==6) // сопоставление
                    {
                        if(is_array($answers))
                        {
                            $countComparisons = ExercisesComparisons::model()->countByAttributes(array('id_exercise'=>$exercise->id));
                            if($countComparisons==count($answers[1]) && $countComparisons==count($answers[2]))
                            {
                                $i = 0;
                                $attrs = array();
                                $attrs['id_exercise'] = $exercise->id;
                                while($countComparisons > $i)
                                {
                                    $attrs['answer_one'] = $answers[1][$i];
                                    $attrs['answer_two'] = $answers[2][$i];
                                    if(!ExercisesComparisons::model()->exists('id_exercise=:id_exercise AND answer_one=:answer_one AND answer_two=:answer_two', $attrs))
                                    {
                                        return false;
                                    }
                                    $i++;
                                }
                                return true;
                            }
                        }
                        
                    }
                    elseif($exercise->id_visual==7) // упорядочивание
                    {
                        if(is_array($answers))
                        {
                            $answers = implode(' ', $answers);
                            if($answers == $rightAnswers[0]->answer)
                            {
                                return true;
                            }
                        }
                        
                    }
                    elseif($exercise->id_visual==8) // Tекст с пробелами
                    {
                        $countSpaces = count($exercise->spaces);
                        if($countSpaces == count($answers['number_spaces']) && $countSpaces == count($answers['answer']))
                        {
                            foreach($answers['number_spaces'] as $index => $space)
                            {
                                $attrs = array('id_exercise'=>$exercise->id, 'answer'=>$answers['answer'][$index], 'space'=>(int)$space);
                                if(!ExercisesListOfAnswers::model()->exists('id_exercise=:id_exercise AND answer=:answer AND number_space=:space AND is_right=1', $attrs))
                                {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                    elseif($exercise->id_visual==9) // Tекст с пробелами с ограничением
                    {
                        if(count($answers) == count($exercise->spaces))
                        {
                            foreach($answers as $space => $answer)
                            {
                                $attrs = array('id_exercise'=>$exercise->id, 'id'=>$answer, 'space'=>$space);
                                if(!ExercisesListOfAnswers::model()->exists('id_exercise=:id_exercise AND id=:id AND number_space=:space AND is_right=1', $attrs))
                                {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                    elseif($exercise->id_visual==10) // точный ответ текст с пробелами
                    {
                        if(count($rightAnswers) == count($answers['number_spaces']))
                        {
                            //print_r($answers['number_spaces']); die;
                            foreach($rightAnswers as $rightAnswer)
                            {
                                $space = $answers['number_spaces'][$rightAnswer->number_space];
                                if($space=='')
                                    return false;
                                if($rightAnswer->reg_exp)
                                {
                                    if(!@preg_match($rightAnswer->answer, $space))
                                    {
                                        return false;
                                    }
                                } else {
                                    if($rightAnswer->answer != $space)
                                    {
                                        return false;
                                    }
                                }
                            }
                            return true;
                        }
                    }
                    elseif($exercise->id_visual==11) // hotmap указание
                    {
                        foreach($rightAnswers as $rightAnswer)
                        {
                            if($rightAnswer->answer == $answers)
                            {
                                return true;
                            }
                        }
                    }
                    elseif($exercise->id_visual==12) // hotmap предметы
                    {
                        if(count($answers)==count($rightAnswers))
                        {
                            $cleanRightAnswers = array();
                            foreach($rightAnswers as $rightAnswer)
                            {
                                $cleanRightAnswers[$rightAnswer->id] = $rightAnswer->answer;
                            }
                            if(!array_diff_assoc($cleanRightAnswers, $answers))
                            {
                                return true;
                            }
                        }
                    }
                    elseif($exercise->id_visual==13) // Мешки
                    {
                        if(count($answers)==count($rightAnswers))
                        {
                            $cleanRightAnswers = array();
                            foreach($rightAnswers as $rightAnswer)
                            {
                                $cleanRightAnswers[$rightAnswer->id] = $rightAnswer->answer;
                            }
                            if(!array_diff_assoc($cleanRightAnswers, $answers))
                            {
                                return true;
                            }
                        }
                    }
                    elseif($exercise->id_visual==14) // hotmap мешки
                    {
                        if(count($answers)==count($rightAnswers))
                        {
                            $cleanRightAnswers = array();
                            foreach($rightAnswers as $rightAnswer)
                            {
                                $cleanRightAnswers[$rightAnswer->id] = $rightAnswer->answer;
                            }
                            if(!array_diff_assoc($cleanRightAnswers, $answers))
                            {
                                return true;
                            }
                        }
                    }
                    elseif($exercise->id_visual==15) // hotmap упорядочивание
                    {
                        if(count($answers)==count($rightAnswers))
                        {
                            $cleanAnswers = array();
                            $i = 1;
                            foreach($answers as $id_answer => $answer)
                            {
                                $cleanAnswers[$id_answer] = $i++;
                            }
                            
                            $cleanRightAnswers = array();
                            foreach($rightAnswers as $rightAnswer)
                            {
                                $cleanRightAnswers[$rightAnswer->id] = $rightAnswer->answer;
                            }
                            if(!array_diff_assoc($cleanRightAnswers, $cleanAnswers))
                            {
                                return true;
                            }
                        }
                    }
                } 
            }
            return false;
        }
        
        public function getRightAnswers() {
            if($this->id)
            {
                $criteria = new CDbCriteria;
                $criteria->compare('id_exercise', $this->id);
                $criteria->compare('is_right', 1);
                $criteria->order = 'reg_exp ASC, id ASC';
                return ExercisesListOfAnswers::model()->findAll($criteria);
            }
            return array();
        }
        
        public function getRightAnswersOrderSpace() {
            if($this->id)
            {
                $criteria = new CDbCriteria;
                $criteria->compare('id_exercise', $this->id);
                $criteria->compare('is_right', 1);
                $criteria->order = 'number_space ASC';
                return ExercisesListOfAnswers::model()->findAll($criteria);
            }
            return array();
        }
        
        public function getIdsRightAnswers() {
            $res = array();
            foreach($this->rightAnswers as $rightAnswer)
            {
                $res[] = $rightAnswer->id;
            }
            return $res;
        }
        
        public function getIdsRightAreas() {
            $res = array();
            foreach($this->rightAnswers as $rightAnswer)
            {
                $res[] = $rightAnswer->answer;
            }
            return $res;
        }
        
        public function getSpaces($order='ASC') {
            $order = $order == 'DESC' ? 'DESC' : 'ASC';
            $res = array();
            if($this->id)
            {
                $query = "SELECT DISTINCT `number_space` FROM `oed_exercises_list_of_answers` WHERE `id_exercise`={$this->id} ORDER BY `number_space` $order";
                $dirties = Yii::app()->db->createCommand($query)->queryAll();
                foreach($dirties as $dirty)
                {
                    $res[] = $dirty['number_space'];
                }
            }
            return $res;
        }
        
        public function getDataSpaces() {
            $res = array();
            foreach($this->spaces as $space)
            {
                $res[$space] = "Пробел $space";
            }
            return $res;
        }
        
        public function AnswersBySpace($space) {
            $answers = array();
            $space = (int) $space;
            if($space > 0 && $this->id)
            {
                $criteria=new CDbCriteria;
                $criteria->compare('id_exercise',$this->id);
                $criteria->compare('number_space', $space);
                $criteria->order = 'RAND()';
                 return ExercisesListOfAnswers::model()->findAll($criteria);
            }
            return array();
        }
        
        public function getSkillsText()
        {
            $skillsNames = array();
            if($this->Skills)
            {
                foreach($this->Skills as $skill)
                {
                    $skillsNames[] = $skill->name;
                }
                return implode(', ', $skillsNames);
            } else {
                return 'Нет';
            }
        }
        
        public function afterSave() {
            if(!$this->isNewRecord)
            {
                foreach($this->idsBlocks as $id_block)
                {
                    $block = GroupOfExercises::model()->findByPk($id_block);
                    if($block)
                    {
                        $block->change_date = date('Y-m-d H:i:s');
                        $block->save(false);
                    }
                }
            }
            parent::afterSave();
        }
}
