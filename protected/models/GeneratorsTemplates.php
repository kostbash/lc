<?php

/**
 * This is the model class for table "oed_generators_templates".
 *
 * The followings are the available columns in table 'oed_generators_templates':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_generator
 * @property string $template
 * @property integer $number_exercises
 */
class GeneratorsTemplates extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_generators_templates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_generator, template, number_exercises', 'required'),
                    	array('template', 'match', 'pattern'=>'/^[x\+\-\*\d\/\(\)\s]+$/i'),
			array('id_user, id_generator, number_exercises', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, id_generator, template, number_exercises', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Variables' => array(self::HAS_MANY, 'GeneratorsTemplatesVariables', 'id_template', 'order'=>'Variables.name DESC'), // ордер деск нужен, чтобы в первую очередь заменялись x88, а не x8
                    'Conditions' => array(self::HAS_MANY, 'GeneratorsTemplatesConditions', 'id_template'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'id_generator' => 'Id Generator',
			'template' => 'Template',
			'number_exercises' => 'Number Exercises',
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_generator',$this->id_generator);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('number_exercises',$this->number_exercises);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeneratorsTemplates the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getForPeplace(){
            $result = array();
            foreach($this->Variables as $variable)
            {
                $result['patterns'][] = "#$variable->name#";
                $result['replacements'][] = $variable->randomNum;
            }
            $result['patterns'][] = '#[^<>]=#'; // заменяем одинарное равно на двойное
            $result['replacements'][] = '==';
            $result['patterns'][] = '#mod#'; // остаток от деления заменяем на php-ный
            $result['replacements'][] = '%';
            return $result;
        }
        
        public function getConditionsArray() {
            $strings = array();
            foreach($this->Conditions as $condition)
            {
                $strings[] = $condition->condition;
            }
            return $strings;
        }
        
        public static function ConditionsMet(array $conditions) {
            foreach($conditions as $condition)
            {
                if(!Generators::executeCode($condition))
                    return false;
            }
            return true;
        }
}
