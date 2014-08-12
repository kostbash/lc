<?php

/**
 * This is the model class for table "oed_group_exercise_and_skills".
 *
 * The followings are the available columns in table 'oed_group_exercise_and_skills':
 * @property integer $id
 * @property integer $id_group
 * @property integer $id_skill
 * @property double $pass_percent
 */
class GroupExerciseAndSkills extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_group_exercise_and_skills';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_group, id_skill, pass_percent', 'required'),
			array('id_group, id_skill', 'numerical', 'integerOnly'=>true),
			array('pass_percent', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_group, id_skill, pass_percent', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Skill'=>array(self::BELONGS_TO, 'Skills', 'id_skill'),
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
			'id_skill' => 'Id Skill',
			'pass_percent' => 'Допустимый процент',
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
		$criteria->compare('id_skill',$this->id_skill);
		$criteria->compare('pass_percent',$this->pass_percent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GroupExerciseAndSkills the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        protected function afterDelete() {
            $group = GroupOfExercises::model()->findByPk($this->id_group);
            if($group)
            {
                $group->change_date = date('Y-m-d H:i:s');
                $group->save(false);
            }
            parent::afterDelete();
        }
}
