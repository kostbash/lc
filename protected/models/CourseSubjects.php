<?php

/**
 * This is the model class for table "oed_course_subjects".
 *
 * The followings are the available columns in table 'oed_course_subjects':
 * @property integer $id
 * @property string $name
 * @property integer $order
 */
class CourseSubjects extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_course_subjects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, order', 'required'),
			array('order', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, order', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            return array(
                'Courses'=>array(self::MANY_MANY, 'Courses', 'oed_courses_and_subjects(id_subject, id_course)'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'order' => 'Очередность',
		);
	}

	public function search()
	{
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('order',$this->order);
            $criteria->order = '`order` ASC';

            $data = new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    'pagination'=>false,
            ));

            $data2 = $data->getData();
            $newSubject = new CourseSubjects();
            $data2[] = $newSubject;
            $data->setData($data2);
            return $data;
	}
        
        public static function listData()
        {
            $criteria=new CDbCriteria;
            $criteria->order = '`order` ASC';
            $subjects = CourseSubjects::model()->findAll($criteria);
            return CHtml::listData($subjects, 'id', 'name');
        }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function maxValueOrder() {
            $id_group = (int) $id_group;
            $sql = "SELECT MAX(`order`) as `max` FROM  `oed_course_subjects`";
            $connection=Yii::app()->db;
            $res = $connection->createCommand($sql)->query()->read();
            return ++$res['max'];
        }
}
