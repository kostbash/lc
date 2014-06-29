<?php

/**
 * This is the model class for table "oed_exercises_comparisons".
 *
 * The followings are the available columns in table 'oed_exercises_comparisons':
 * @property integer $id
 * @property integer $id_exercise
 * @property integer $answer_one
 * @property integer $answer_two
 */
class ExercisesComparisons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_exercises_comparisons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_exercise, answer_one, answer_two', 'required'),
			array('id_exercise, answer_one, answer_two', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_exercise, answer_one, answer_two', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'AnswerOne'=>array(self::BELONGS_TO, 'ExercisesListOfAnswers', 'answer_one'),
                    'AnswerTwo'=>array(self::BELONGS_TO, 'ExercisesListOfAnswers', 'answer_two'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_exercise' => 'Id Exercise',
			'answer_one' => 'Answer One',
			'answer_two' => 'Answer Two',
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
		$criteria->compare('id_exercise',$this->id_exercise);
		$criteria->compare('answer_one',$this->answer_one);
		$criteria->compare('answer_two',$this->answer_two);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExercisesComparisons the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function afterDelete() {
            $this->AnswerOne->delete();
            $this->AnswerTwo->delete();
            parent::afterDelete();
        }
}
