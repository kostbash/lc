<?php

/**
 * This is the model class for table "oed_variables".
 *
 * The followings are the available columns in table 'oed_variables':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $id_course
 */
class Variables extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_variables';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, id_course', 'required'),
			array('id_course', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
            array('name', 'in',
                'range'=>array('block','code','test_section', 'count'),
                'allowEmpty'=>false,
                'strict'=>false,
                'not'=>true,
                'message'=>'Запрещенное название переменной'
            ),
            //array('name', 'UniqueAttributesValidator', 'with'=>'id_course'),
			array('type', 'length', 'max'=>10),
            array('default_value', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, id_course', 'safe', 'on'=>'search'),
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
            'UserValue'=>array(self::HAS_MANY, 'VarUserValue', 'oed_var_user_value(id_course, variable_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'type' => 'Type',
			'id_course' => 'Id Course',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('id_course',$this->id_course);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Variables the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
