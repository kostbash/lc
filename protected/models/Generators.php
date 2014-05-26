<?php

/**
 * This is the model class for table "oed_generators".
 *
 * The followings are the available columns in table 'oed_generators':
 * @property integer $id
 * @property string $name
 */
class Generators extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_generators';
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
			'name' => 'Name',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Generators the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        function getTemplate()
        {
            return GeneratorsTemplates::model()->findByAttributes(array('id_generator'=>$this->id, 'id_user'=>Yii::app()->user->id));
        }
        
        static function ListGenerators($id, $type='group') {
            $gens = Generators::model()->findAll();
            $list = '';
            if($gens)
            {
                foreach($gens as $gen)
                {
                    $list .= "<li data-id='$gen->id'>". CHtml::link($gen->name, array('/admin/generators/settings', 'id'=>$gen->id, "id_$type"=>$id)) ."</li>";
                }
            } else {
                $list .= "<li><a href='javascript:void(0)'>Нет генераторов</a></li>";
            }
            return $list;
        }
        
        static function getConvertStrings(array $patterns, array $replacements, $strings) {
            return preg_replace($patterns, $replacements, $strings);
        }
        
        static function executeCode($str)
        {
            return eval("return $str;");
        }
}
