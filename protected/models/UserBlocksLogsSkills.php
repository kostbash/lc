<?php

/**
 * This is the model class for table "oed_user_blocks_logs_skills".
 *
 * The followings are the available columns in table 'oed_user_blocks_logs_skills':
 * @property integer $id
 * @property integer $id_log
 * @property integer $id_skill
 * @property integer $achieved_percent
 * @property integer $need_percent
 */
class UserBlocksLogsSkills extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_user_blocks_logs_skills';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_log, id_skill, achieved_percent, need_percent', 'required'),
			array('id_log, id_skill, achieved_percent, need_percent', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_log, id_skill, achieved_percent, need_percent', 'safe', 'on'=>'search'),
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
			'id_log' => 'Id Log',
			'id_skill' => 'Id Skill',
			'achieved_percent' => 'Achieved Percent',
			'need_percent' => 'Need Percent',
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
		$criteria->compare('id_log',$this->id_log);
		$criteria->compare('id_skill',$this->id_skill);
		$criteria->compare('achieved_percent',$this->achieved_percent);
		$criteria->compare('need_percent',$this->need_percent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserBlocksLogsSkills the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
