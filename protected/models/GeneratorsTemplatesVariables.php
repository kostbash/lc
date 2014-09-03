<?php

/**
 * This is the model class for table "oed_generators_templates_variables".
 *
 * The followings are the available columns in table 'oed_generators_templates_variables':
 * @property integer $id
 * @property integer $id_template
 * @property string $name
 * @property integer $value_min
 * @property integer $value_max
 */
class GeneratorsTemplatesVariables extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_generators_templates_variables';
	}

        public static $minVal = 0;
        public static $maxVal = 100;
        
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_template, name', 'required'),
			array('id_template, value_min, value_max, values_type', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>10),
			array('values', 'length', 'max'=>500),
                        array('values', 'match', 'pattern'=>'/^[\d ,]+$/i'),
			array('id, id_template, name, value_min, value_max', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'value_min' => 'Value Min',
			'value_max' => 'Value Max',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_template',$this->id_template);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value_min',$this->value_min);
		$criteria->compare('value_max',$this->value_max);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getHtml($num)
        {
            $minVal = $this->value_min ? $this->value_min : self::$minVal;
            $maxVal = $this->value_max ? $this->value_max : self::$maxVal;
            $attrMin = array('maxlength'=>11, 'class'=>'form-control only-number', 'placeholder' => 'Введите мин.значение');
            $attrMax = array('maxlength'=>11, 'class'=>'form-control only-number', 'placeholder' => 'Введите макс.значение');
            $attrEnumeration = array('maxlength'=>500, 'class'=>'form-control enumeration', 'placeholder' => 'Введите значения через запятую');
            if($this->values_type==1)
            {
                $attrEnumeration['disabled'] = 'disabled';
            } elseif($this->values_type==2)
            {
                $attrMin['disabled'] = 'disabled';
                $attrMax['disabled'] = 'disabled';
            }
            return
            "<div class='row variable' data-num='$num'>
                <div class='col-lg-2 col-md-2 name' data-name='$this->name'>
                    ".CHtml::hiddenField(__CLASS__."[$num][name]", $this->name)."
                    ".CHtml::label($this->name, __CLASS__."_{$num}_value_min")."
                </div>
                <div class='col-lg-2 col-md-2'>
                    ".CHtml::textField(__CLASS__."[$num][value_min]", $minVal, $attrMin)."
                    <div class='errorMessage'></div>
                </div>
                <div class='col-lg-2 col-md-2'>
                    ".CHtml::textField(__CLASS__."[$num][value_max]", $maxVal, $attrMax)."
                    <div class='errorMessage'></div>
                </div>
                <div class='col-lg-2 col-md-2'>
                    ".CHtml::textField(__CLASS__."[$num][values]", $this->values, $attrEnumeration)."
                    <div class='errorMessage'></div>
                </div>
                <div class='col-lg-2 col-md-2'>
                    ".CHtml::radioButton(__CLASS__."[$num][values_type]", $this->values_type==1, array('value'=>1, 'class'=>'type-min-max', 'id'=>"GeneratorsTemplatesVariables_{$num}_values_type_1", 'style'=>'margin-top: 3px; float: left; width: 10%;'))."
                    ".CHtml::label("Мин. и макс. значение", "GeneratorsTemplatesVariables_{$num}_values_type_1", array('style'=>'float: left; margin-left: 2%; width: 88%; line-height: 17px; font-size: 12px;'))."
                </div>
                <div class='col-lg-2 col-md-2'>
                    ".CHtml::radioButton(__CLASS__."[$num][values_type]", $this->values_type==2, array('value'=>2, 'class'=>'type-enumeration',  'id'=>"GeneratorsTemplatesVariables_{$num}_values_type_2", 'style'=>'margin-top: 3px; float: left; width: 10%;'))."
                    ".CHtml::label("Перечисление через запятую", "GeneratorsTemplatesVariables_{$num}_values_type_2", array('style'=>'float: left; margin-left: 2%; width: 88%; line-height: 17px; font-size: 12px;'))."
                </div>
            </div>";
        }
        
        public function getRandomNum() {
            if($this->values_type == 1)
            {
                return mt_rand($this->value_min, $this->value_max);
            }
            elseif($this->values_type == 2)
            {
                preg_match_all("#(\d+)#", $this->values, $values);
                $index = array_rand($values[0]);
                return $values[0][$index];
            }
        }
}
