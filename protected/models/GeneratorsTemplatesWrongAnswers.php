<?php

/**
 * This is the model class for table "oed_generators_templates_wrong_answers".
 *
 * The followings are the available columns in table 'oed_generators_templates_wrong_answers':
 * @property integer $id
 * @property integer $id_template
 * @property string $wrong_answer
 */
class GeneratorsTemplatesWrongAnswers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_generators_templates_wrong_answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_template, wrong_answer', 'required'),
			array('id_template', 'numerical', 'integerOnly'=>true),
			array('wrong_answer', 'length', 'max'=>255),
                        array('wrong_answer', 'match', 'pattern'=>'/^[x\+\-\*\d\/\(\)\s]+$/i'),
			array('id, id_template, wrong_answer', 'safe', 'on'=>'search'),
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
			'id_template' => 'Id Template',
			'wrong_answer' => 'Wrong Answer',
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
		$criteria->compare('id_template',$this->id_template);
		$criteria->compare('wrong_answer',$this->wrong_answer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeneratorsTemplatesWrongAnswers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}