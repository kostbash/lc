<?php

/**
 * This is the model class for table "oed_skills_steps".
 *
 * The followings are the available columns in table 'oed_skills_steps':
 * @property integer $id
 * @property integer $id_course
 * @property string $name
 * @property string $condition
 */
class SkillsSteps extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_skills_steps';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_skill, name', 'required'),
			array('id_skill', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('condition', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_course, name, condition', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'condition' => 'Condition',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('condition',$this->condition,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SkillsSteps the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function searchSteps()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('id_skill', $this->id_skill);

        $data =  new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>false,
        ));
        $data2 = $data->getData();
        $newStep = new SkillsSteps();
        $data2[] = $newStep;
        $data->setData($data2);
        return $data;

    }

    function getCanDelete() {
        return 1;
    }
}
