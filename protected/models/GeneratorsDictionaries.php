<?php

/**
 * This is the model class for table "oed_generators_dictionaries".
 *
 * The followings are the available columns in table 'oed_generators_dictionaries':
 * @property integer $id
 * @property integer $id_generator
 * @property string $name
 */
class GeneratorsDictionaries extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_generators_dictionaries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_generator, name', 'required'),
			array('id_generator', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_generator, name', 'safe', 'on'=>'search'),
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
                    'Tags'=>array(self::HAS_MANY, 'GeneratorsTags', 'id_dictionary'),
                    'Words'=>array(self::HAS_MANY, 'GeneratorsWords', 'id_dictionary'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_generator' => 'Id Generator',
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
		$criteria->compare('id_generator',$this->id_generator);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeneratorsDictionaries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getDataDicitionaries($id_gen) {
            $dictionaries = GeneratorsDictionaries::model()->findAll('id_generator=:id_generator', array('id_generator'=>$id_gen));
            return $dictionaries ? CHtml::listData($dictionaries, 'id', 'name') : array();
        }
        
        public function afterDelete() {
            foreach($this->Tags as $tag)
            {
                $tag->delete();
            }
            foreach($this->Words as $word)
            {
                $word->delete();
            }
            parent::afterDelete();
        }
}
