<?php

/**
 * This is the model class for table "oed_params_course_vars".
 *
 * The followings are the available columns in table 'oed_params_course_vars':
 * @property integer $id
 * @property integer $id_course
 * @property integer $MaxTasksCountInBlock
 * @property integer $MaxBlocksCountInLesson
 * @property integer $Threshold
 * @property integer $FailReps
 * @property integer $TheoryShowReps
 */
class ParamsCourseVars extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_params_course_vars';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(' id_course', 'required'),
			array('MaxTasksCountInBlock, MaxBlocksCountInLesson, FailReps, TheoryShowReps', 'numerical', 'integerOnly'=>true, 'min'=>1),
			// The following rule is used by search().
            array('Threshold', 'numerical', 'min'=>50),
			// @todo Please remove those attributes that should not be searched.
			array('id, id_course, MaxTasksCountInBlock, MaxBlocksCountInLesson, Threshold, FailReps, TheoryShowReps', 'safe', 'on'=>'search'),
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
			'id_course' => 'Id Course',
			'MaxTasksCountInBlock' => 'Max Tasks Count In Block',
			'MaxBlocksCountInLesson' => 'Max Blocks Count In Lesson',
			'Threshold' => 'Threshold',
			'FailReps' => 'Fail Reps',
			'TheoryShowReps' => 'Theory Show Reps',
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
		$criteria->compare('id_course',$this->id_course);
		$criteria->compare('MaxTasksCountInBlock',$this->MaxTasksCountInBlock);
		$criteria->compare('MaxBlocksCountInLesson',$this->MaxBlocksCountInLesson);
		$criteria->compare('Threshold',$this->Threshold);
		$criteria->compare('FailReps',$this->FailReps);
		$criteria->compare('TheoryShowReps',$this->TheoryShowReps);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ParamsCourseVars the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
