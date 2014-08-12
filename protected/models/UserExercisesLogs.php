<?php

/**
 * This is the model class for table "oed_user_exercises_logs".
 *
 * The followings are the available columns in table 'oed_user_exercises_logs':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_course
 * @property integer $id_theme
 * @property integer $id_lesson
 * @property integer $id_block
 * @property integer $id_exercise
 * @property string $date
 * @property string $time
 * @property integer $duration
 * @property integer $right
 */
class UserExercisesLogs extends CActiveRecord
{
        public $new;
        public $id_block_type;
        public $id_skill;
    
	public function tableName()
	{
		return 'oed_user_exercises_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_course, id_theme, id_lesson, id_block, id_exercise, date, time, duration, right, answer', 'required'),
			array('id_user, id_course, id_theme, id_lesson, id_block, id_exercise, duration, right', 'numerical', 'integerOnly'=>true),
                        array('answer', 'safe'),
			array('id, id_user, id_course, id_theme, id_lesson, id_block, id_exercise, date, time, duration, right, new, id_block_type, id_skill', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
                    'Course'=>array(self::BELONGS_TO, 'Courses', 'id_course'),
                    'Theme'=>array(self::BELONGS_TO, 'GroupOfLessons', 'id_theme'),
                    'Lesson'=>array(self::BELONGS_TO, 'Lessons', 'id_lesson'),
                    'Block'=>array(self::BELONGS_TO, 'GroupOfExercises', 'id_block'),
                    'Exercise'=>array(self::BELONGS_TO, 'Exercises', 'id_exercise'),
                    'LogsAndTeachers'=>array(self::HAS_MANY, 'UserExercisesLogsAndTeacher', 'id_log'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'id_course' => 'Id Course',
			'id_theme' => 'Id Theme',
			'id_lesson' => 'Id Lesson',
			'id_block' => 'Id Block',
			'id_exercise' => 'Id Exercise',
			'niceDate' => 'Дата',
			'time' => 'Время',
			'duration' => 'Длительность выполнения, сек',
			'rightText' => 'Правильно',
                        'courseName'=>'Курс',
                        'lessonName'=>'Урок',
                        'blockName'=>'Блок',
                        'exerciseName'=>'Задание и ответ',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
                $with = array();
		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('t.id_course',$this->id_course);
		$criteria->compare('id_theme',$this->id_theme);
		$criteria->compare('id_lesson',$this->id_lesson);
		$criteria->compare('id_block',$this->id_block);
		$criteria->compare('id_exercise',$this->id_exercise);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('right',$this->right);
                
                $with[] = 'LogsAndTeachers';
                $criteria->compare('LogsAndTeachers.id_teacher', Yii::app()->user->id, 'AND');
                $criteria->compare('LogsAndTeachers.new', $this->new, 'AND');
                if(isset($this->id_block_type))
                {
                    $with[] = 'Block';
                    $criteria->compare('Block.type', $this->id_block_type, 'AND');
                }
                
//                if(isset($this->id_skill))
//                {
//                    $with[] = 'Exercise';
//                    $criteria->compare('Exercise.Skills.id_skill', $this->id_skill, 'AND');
//                }

                $criteria->order = 'LogsAndTeachers.new DESC, date DESC, time DESC';
                $criteria->with = $with;
                $criteria->together = true;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        // удаляем информацию о том что запись новая
        public function madeOldRecord(CActiveDataProvider $dataProvider)
        {
            $logs = $dataProvider->getData();
            foreach($logs as $log)
            {
                if($log->isNew)
                {
                    $teacherLog = UserExercisesLogsAndTeacher::model()->findByAttributes(array('id_log'=>$log->id, 'id_teacher'=>Yii::app()->user->id));
                    $teacherLog->new = 0;
                    $teacherLog->save();
                }
            }
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserExercisesLogs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getNiceDate()
        {
            return date('d.m.Y', strtotime($this->date));
        }
        
        public function getRightText()
        {
            if($this->right)
                return 'Да';
            return 'Нет';
        }
        
        public function getCourseName()
        {
            if(!$this->Course)
                return 'Удален';
            $change = '';
            if($this->Course->change_date > $this->date)
            {
                $change = ' Изменен';
            }
            return $this->Course->name.$change;
        }
        
        public function getLessonName()
        {
            if(!$this->Lesson)
                return 'Удален';
            $change = '';
            if($this->Lesson->change_date > $this->date)
            {
                $change = ' Изменен';
            }
            return $this->Lesson->name.$change;
        }
        
        public function getBlockName()
        {
            if(!$this->Block)
                return 'Удален';
            $change = '';
            if($this->Block->change_date > $this->date)
            {
                $change = ' Изменен';
            }
            return $this->Block->name.$change;
        }
        
        public function getIsNew()
        {
            return UserExercisesLogsAndTeacher::model()->exists("id_log=:id_log AND id_teacher=:id_teacher AND new=:new", array('id_log'=>$this->id, 'id_teacher'=>Yii::app()->user->id, 'new'=>1));
        }
        
        public function getCountNew()
        {
            $criteria=new CDbCriteria;
            $criteria->compare('id_user', $this->id_user, 'AND');
            $criteria->compare('LogsAndTeachers.id_teacher', Yii::app()->user->id);
            $criteria->compare('LogsAndTeachers.new', 1);
            $criteria->with = 'LogsAndTeachers';
            $criteria->together = true;
            return UserExercisesLogs::model()->count($criteria);
        }
        
        public function getClassLog()
        {
            if($this->isNew)
            {
                return 'new-log';
            }
        }
        
        public function getAnswerUnserialize()
        {
            return unserialize($this->answer);
        }
        
        
        public function getExerciseName()
        {
            if(!$this->Exercise)
                return 'Удален';
            $change = '';
            if($this->Exercise->change_date > $this->date)
            {
                $change = ' Изменен';
            }
            return CHtml::link('Просмотреть задание', array('/admin/exerciseslogs/view', 'id'=>$this->id), array('target'=>'_blank')).$change;
        }
}
