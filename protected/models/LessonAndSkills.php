<?php

/**
 * This is the model class for table "oed_lesson_and_skills".
 *
 * The followings are the available columns in table 'oed_lesson_and_skills':
 * @property integer $id
 * @property integer $id_lesson
 * @property integer $id_skill
 * @property double $pass_percent
 */
class LessonAndSkills extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_lesson_and_skills';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_lesson, id_skill', 'required'),
			array('id_lesson, id_skill', 'numerical', 'integerOnly'=>true),
			array('pass_percent', 'numerical'),
			array('id, id_lesson, id_skill, pass_percent', 'safe', 'on'=>'search'),
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
                    'Lesson'=>array(self::BELONGS_TO, 'Lessons', 'id_lesson'),
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
			'id_lesson' => 'Id Lesson',
			'id_skill' => 'Id Skill',
			'pass_percent' => 'Мин. допустимый процент',
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
		$criteria->compare('id_lesson',$this->id_lesson);
		$criteria->compare('id_skill',$this->id_skill);
		$criteria->compare('pass_percent',$this->pass_percent);

                $data =  new CActiveDataProvider($this, array(
                        'criteria'=>$criteria,
                ));
                $data2 = $data->getData();
                $newSkill = new LessonAndSkills();
                $data2[] = $newSkill;
                $data->setData($data2);
                return $data;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LessonAndSkills the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getListDataPercents() {
            for($i=0.01; $i<=1.01; $i += 0.01)
                $mass["$i"] = $i * 100 . "%";
            return $mass;
        }
        
}
