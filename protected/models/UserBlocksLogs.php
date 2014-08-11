<?php

/**
 * This is the model class for table "oed_user_blocks_logs".
 *
 * The followings are the available columns in table 'oed_user_blocks_logs':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_course
 * @property integer $id_theme
 * @property integer $id_lesson
 * @property integer $id_block
 * @property string $date
 * @property string $time
 * @property integer $duration
 * @property integer $passed
 */
class UserBlocksLogs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_user_blocks_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_course, id_theme, id_lesson, id_block, date, time, duration, passed', 'required'),
			array('id_user, id_course, id_theme, id_lesson, id_block, duration, passed', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, id_course, id_theme, id_lesson, id_block, date, time, duration, passed', 'safe', 'on'=>'search'),
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
			'id_user' => 'Id User',
			'id_course' => 'Id Course',
			'id_theme' => 'Id Theme',
			'id_lesson' => 'Id Lesson',
			'id_block' => 'Id Block',
			'date' => 'Date',
			'time' => 'Time',
			'duration' => 'Duration',
			'passed' => 'Passed',
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
		$criteria->compare('id_course',$this->id_course);
		$criteria->compare('id_theme',$this->id_theme);
		$criteria->compare('id_lesson',$this->id_lesson);
		$criteria->compare('id_block',$this->id_block);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('passed',$this->passed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserBlocksLogs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
