<?php

/**
 * This is the model class for table "oed_user_and_exercise_groups".
 *
 * The followings are the available columns in table 'oed_user_and_exercise_groups':
 * @property integer $id
 * @property integer $id_user_and_lesson
 * @property integer $id_exercise_group
 */
class UserAndExerciseGroups extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_user_and_exercise_groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user_and_lesson, id_exercise_group', 'required'),
			array('id_user_and_lesson, id_exercise_group, number_all, number_right', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user_and_lesson, id_exercise_group', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'UserAndLesson'=>array(self::BELONGS_TO, 'UserAndLessons', 'id_user_and_lesson'),
                    'Group'=>array(self::BELONGS_TO, 'GroupOfExercises', 'id_exercise_group'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user_and_lesson' => 'Id User And Lesson',
			'id_exercise_group' => 'Id Exercise Group',
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
		$criteria->compare('id_user_and_lesson',$this->id_user_and_lesson);
		$criteria->compare('id_exercise_group',$this->id_exercise_group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OedUserAndExerciseGroups the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function ExistUserAndGroup($id_user_and_lesson, $id_group)
        {
            return UserAndExerciseGroups::model()->exists('`id_user_and_lesson`=:id_user_and_lesson AND `id_exercise_group`=:id_group', array('id_user_and_lesson'=>$id_user_and_lesson, 'id_group'=>$id_group));
        }
        
        public function getNextGroup() {
            $lessonExerciseGroup = LessonAndExerciseGroup::model()->findByAttributes(array('id_lesson'=>$this->UserAndLesson->id_lesson, 'id_group_exercises'=>$this->id_exercise_group));
            return LessonAndExerciseGroup::model()->find('`order` > :order AND `id_lesson`=:id_lesson ORDER BY `order` ASC', array('order'=>$lessonExerciseGroup->order, 'id_lesson'=>$lessonExerciseGroup->id_lesson));
        }
}
