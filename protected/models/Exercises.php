<?php

/**
 * This is the model class for table "oed_exercises".
 *
 * The followings are the available columns in table 'oed_exercises':
 * @property integer $id
 * @property string $condition
 * @property string $correct_answers
 * @property integer $difficulty
 */
class Exercises extends CActiveRecord
{

        public $difficultyMass = array(0,1,2,3,4,5,6,7,8,9,10);
        static $needAnswer = array('1'=>'Да', '0'=>'Нет');
        public $SkillsIds;
        public $limit;
        public $number;
        public $pageSize = 10;
        public static $pageSizes = array(
            //'0' => 'Все',
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
			array('condition', 'required'),
			array('difficulty, limit, course_creator_id', 'numerical', 'integerOnly'=>true),
                        array('correct_answers', 'safe'),
                        array('need_answer', 'boolean'),
			array('id, condition, limit, correct_answers, SkillsIds, difficulty, pageSize', 'safe', 'on'=>'search'),
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
                        'need_answer'=>'Треб. ответ',
                        'pageSize'=>'Кол-во выводимых заданий',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
            $criteria=new CDbCriteria;
            $params = array();
            if($this->id)
                $criteria->compare('id',$this->id);
            else
                $course_id = $this->course_creator_id ? $this->course_creator_id : 0;
            $criteria->compare('condition',$this->condition,true);
            $criteria->compare('correct_answers',$this->correct_answers,true);
            $criteria->compare('difficulty',$this->difficulty);
            $criteria->compare('need_answer',$this->need_answer);
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
            $data = new CActiveDataProvider($this, $params);
            $data2 = $data->getData();
            $data2[] = new Exercises();
            $data->setData($data2);
            return $data;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Exercises the static model class
	 */
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
        
        public function getCanDelete() {
            if($this->ExercisesGroup)
                return 0;
            return 1;
        }
}
