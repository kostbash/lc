<?php

/**
 * This is the model class for table "oed_generators_templates_conditions".
 *
 * The followings are the available columns in table 'oed_generators_templates_conditions':
 * @property integer $id
 * @property integer $id_template
 * @property string $condition
 */
class GeneratorsTemplatesConditions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_generators_templates_conditions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_template, condition', 'required'),
			array('id_template', 'numerical', 'integerOnly'=>true),
			array('condition', 'length', 'max'=>255),
			array('condition', 'match', 'pattern'=>'/^[x\+\-\*\d\/\(\)\s=mod]+$/i'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_template, condition', 'safe', 'on'=>'search'),
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
		$criteria->compare('id_template',$this->id_template);
		$criteria->compare('condition',$this->condition,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function getHtml($num)
        {
            return
            "<div class='row condition' data-num='$num'>
                <div class='col-lg-2 col-md-2'>
                    ".CHtml::label("Условие $num", __CLASS__."_{$num}_condition")."
                </div>
                <div class='col-lg-4 col-md-4'>
                    ".CHtml::textField(__CLASS__."[$num][condition]", $this->condition, array('maxlength'=>255, 'class'=>'form-control', 'placeholder' => 'Введите условие'))."
                    <div class='errorMessage'></div>
                </div>
                <div class='col-lg-2 col-md-2'>
                    ".CHtml::link("Удалить<i class='glyphicon glyphicon-remove'></i>", '#', array('class'=>'btn btn-danger btn-icon-right remove-condition'))."
                </div>
            </div>";
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
