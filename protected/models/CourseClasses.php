<?php

/**
 * This is the model class for table "oed_course_classes".
 *
 * The followings are the available columns in table 'oed_course_classes':
 * @property integer $id
 * @property string $name
 */
class CourseClasses extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_course_classes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            return array(
                'Courses'=>array(self::HAS_MANY, 'Courses', 'id_class'),
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
		);
	}
        
	public function search()
	{
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('name',$this->name,true);

            $data = new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    'pagination'=>false,
            ));

            $data2 = $data->getData();
            $newClass = new CourseClasses();
            $data2[] = $newClass;
            $data->setData($data2);
            return $data;
	}

        public static function listData()
        {
            $classes = CourseClasses::model()->findAll();
            return CHtml::listData($classes, 'id', 'name');
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
