<?php

/**
 * This is the model class for table "oed_group_and_exercises".
 *
 * The followings are the available columns in table 'oed_group_and_exercises':
 * @property integer $id
 * @property integer $id_group
 * @property integer $id_exercise
 */
class GroupAndExercises extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_group_and_exercises';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_group, id_exercise', 'required'),
			array('id_group, id_exercise', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_group, id_exercise', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            return array(
                'Lesson'=>array(self::MANY_MANY, 'Lessons', 'oed_lesson_and_exercise_group(id_lesson, id_group_exercises)'),
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
			'id_exercise' => 'Id Exercise',
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
		$criteria->compare('id_exercise',$this->id_exercise);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GroupAndExercises the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
             
        public static function maxValueOrder($id_group) {
            $id_group = (int) $id_group;
            $sql = "SELECT MAX( `order`) as `max` FROM  `oed_group_and_exercises` WHERE  `id_group` = $id_group";
            $connection=Yii::app()->db;
            $res = $connection->createCommand($sql)->query()->read();
            return ++$res['max'];
        }
        
}
