<?php

/**
 * This is the model class for table "oed_exercises_list_of_anwers".
 *
 * The followings are the available columns in table 'oed_exercises_list_of_anwers':
 * @property integer $id
 * @property integer $id_exercise
 * @property string $answer
 */
class ExercisesListOfAnswers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_exercises_list_of_answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                    array('id_exercise', 'required'),
                    array('answer', 'requiredSpacePass'),
                    array('name', 'length', 'max'=>255),
                    array('id_exercise, is_right, reg_exp, number_space, id_question', 'numerical', 'integerOnly'=>true),
                    array('id, id_exercise, answer', 'safe', 'on'=>'search'),
		);
	}

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
			'id_exercise' => 'Id Exercise',
			'answer' => 'Answer',
		);
	}

        public function RequiredSpacePass($attribute, $params)
        {
            if($this->$attribute == '')
              $this->addError($attribute, "Необходимо заполнить поле $attribute");
        }

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
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
	 * @return ExercisesListOfAnwers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
