<?php

/**
 * This is the model class for table "oed_course_and_lesson_group".
 *
 * The followings are the available columns in table 'oed_course_and_lesson_group':
 * @property integer $id
 * @property integer $id_course
 * @property integer $id_group_lesson
 */
class CourseAndLessonGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_course_and_lesson_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_course, id_group_lesson', 'required'),
			array('id_course, id_group_lesson', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_course, id_group_lesson', 'safe', 'on'=>'search'),
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
                    'Course' => array(self::BELONGS_TO, 'Courses', 'id_course'),
                    'GroupAndLessons' => array(self::HAS_MANY, 'GroupAndLessons', array('id_group'=>'id_group_lesson'), 'order'=>'GroupAndLessons.order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_course' => 'Id Course',
			'id_group_lesson' => 'Id Group Lesson',
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
		$criteria->compare('id_course',$this->id_course);
		$criteria->compare('id_group_lesson',$this->id_group_lesson);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CourseAndLessonGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function maxValueOrder($id_course) {
            $id_course = (int) $id_course;
            $sql = "SELECT MAX( `order`) as `max` FROM  `oed_course_and_lesson_group` WHERE  `id_course` = $id_course";
            $connection=Yii::app()->db;
            $res = $connection->createCommand($sql)->query()->read();
            return ++$res['max'];
        }
        
}
