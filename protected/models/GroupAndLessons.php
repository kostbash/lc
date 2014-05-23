<?php

/**
 * This is the model class for table "oed_group_and_lessons".
 *
 * The followings are the available columns in table 'oed_group_and_lessons':
 * @property integer $id
 * @property integer $id_group
 * @property integer $id_lesson
 */
class GroupAndLessons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_group_and_lessons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_group, id_lesson', 'required'),
			array('id_group, id_lesson', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_group, id_lesson', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Course'=>array(self::MANY_MANY, 'Courses', 'oed_course_and_lesson_group(id_course, id_group_lesson)'),
                    'Lesson'=>array(self::BELONGS_TO, 'Lessons', 'id_lesson'),
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
			'id_lesson' => 'Id Lesson',
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
		$criteria->compare('id_lesson',$this->id_lesson);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GroupAndLessons the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function maxValueOrder($id_group) {
            $id_group = (int) $id_group;
            $sql = "SELECT MAX( `order`) as `max` FROM  `oed_group_and_lessons` WHERE  `id_group` = $id_group";
            $connection=Yii::app()->db;
            $res = $connection->createCommand($sql)->query()->read();
            return ++$res['max'];
        }
}
