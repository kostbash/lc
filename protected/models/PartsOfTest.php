<?php

/**
 * This is the model class for table "oed_parts_of_test".
 *
 * The followings are the available columns in table 'oed_parts_of_test':
 * @property integer $id
 * @property integer $id_group
 * @property integer $difficulty
 * @property integer $limit
 * @property integer $order
 */
class PartsOfTest extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_parts_of_test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_group, order', 'required'),
			array('id_group, limit, order', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_group, limit, order', 'safe', 'on'=>'search'),
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
                    'Group'=>array(self::BELONGS_TO, 'GroupOfExercises', 'id_group'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_group' => 'Id Group',
                        'CountExercise'=>'Число заданий',
			'limit' => 'Limit',
			'order' => 'Order',
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
		$criteria->compare('id_group',$this->id_group);
		$criteria->compare('limit',$this->limit);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartsOfTest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getCountExercises() {
            return PartsOfTestAndExercises::model()->countByAttributes(array('id_part'=>$this->id));
        }
        
        public static function maxValueOrder($id_group) {
            $id_group = (int) $id_group;
            $sql = "SELECT MAX( `order`) as `max` FROM  `oed_parts_of_test` WHERE  `id_group` = $id_group";
            $connection=Yii::app()->db;
            $res = $connection->createCommand($sql)->query()->read();
            return ++$res['max'];
        }
        
        public function afterDelete() {
            //$this->Group->changeDate();
            PartsOfTestAndExercises::model()->deleteAllByAttributes(array('id_part'=>$this->id));
            parent::afterDelete();
        }
        
        public function getExercises($rand=true, $limit=true) {
            $partExercises = PartsOfTestAndExercises::model()->findAllByAttributes(array('id_part'=>$this->id));
            $ids = array();
            foreach($partExercises as $partExercise)
            {
               $ids[] = $partExercise->id_exercise;
            }
            $criteria = new CDbCriteria;
            if($limit && $this->limit)
                $criteria->limit = $this->limit;
            $criteria->addInCondition('id', $ids);
            if($rand)
                $criteria->order = 'RAND()';
            return Exercises::model()->findAll($criteria);
        }
}
