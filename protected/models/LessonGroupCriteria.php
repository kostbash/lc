<?php

/**
 * This is the model class for table "oed_lesson_group_criteria".
 *
 * The followings are the available columns in table 'oed_lesson_group_criteria':
 * @property integer $id
 * @property integer $id_group
 * @property integer $difficulty
 */
class LessonGroupCriteria extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_lesson_group_criteria';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_group', 'required'),
			array('id_group, difficulty, limit', 'numerical', 'integerOnly'=>true),
			array('id, id_group, difficulty', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(

                );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_group' => 'Id Group',
			'difficulty' => 'Difficulty',
                        'need_answer'=>'need_answer',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('id_group',$this->id_group);
		$criteria->compare('difficulty',$this->difficulty);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function maxValueOrder($id_group) {
            $id_group = (int) $id_group;
            $sql = "SELECT MAX( `order`) as `max` FROM  `oed_lesson_group_criteria` WHERE  `id_group` = $id_group";
            $connection=Yii::app()->db;
            $res = $connection->createCommand($sql)->query()->read();
            return ++$res['max'];
        }
        
        public function getExercises() {
            $criteria=new CDbCriteria;
            $criteria->compare('difficulty',$this->difficulty);
            $criteria->compare('need_answer', 1);
            if($this->limit)
                $criteria->limit = $this->limit;
            $criteria->order = "RAND()";
            return Exercises::model()->findAll($criteria);
        }
        
        public function getLesson() {
            $lessonAndGroup = LessonAndExerciseGroup::model()->findByAttributes(array('id_group_exercises'=>$this->id_group));
            if($lessonAndGroup)
                $lesson = Lessons::model()->findByPk($lessonAndGroup->id_lesson);
                if($lesson)
                    return $lesson;
            return false;
        }
}
