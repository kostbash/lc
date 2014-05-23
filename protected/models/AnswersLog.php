<?php

/**
 * This is the model class for table "oed_answers_log".
 *
 * The followings are the available columns in table 'oed_answers_log':
 * @property integer $id
 * @property string $ip
 * @property string $date
 * @property integer $id_course
 * @property integer $id_lesson_group
 * @property integer $id_lesson
 * @property integer $id_exercise_group
 * @property integer $id_exercise
 * @property string $answer
 */
class AnswersLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_answers_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip, date, id_course, id_lesson_group, id_lesson, id_exercise_group, id_exercise, answer', 'required'),
			array('id_course, id_lesson_group, id_lesson, id_exercise_group, id_exercise', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>15),
			array('answer', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ip, date, id_course, id_lesson_group, id_lesson, id_exercise_group, id_exercise, answer', 'safe', 'on'=>'search'),
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
			'ip' => 'Ip',
			'date' => 'Date',
			'id_course' => 'Id Course',
			'id_lesson_group' => 'Id Lesson Group',
			'id_lesson' => 'Id Lesson',
			'id_exercise_group' => 'Id Exercise Group',
			'id_exercise' => 'Id Exercise',
			'answer' => 'Answer',
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
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('id_course',$this->id_course);
		$criteria->compare('id_lesson_group',$this->id_lesson_group);
		$criteria->compare('id_lesson',$this->id_lesson);
		$criteria->compare('id_exercise_group',$this->id_exercise_group);
		$criteria->compare('id_exercise',$this->id_exercise);
		$criteria->compare('answer',$this->answer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AnswersLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
