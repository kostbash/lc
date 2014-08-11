<?php

/**
 * This is the model class for table "oed_group_of_exercises".
 *
 * The followings are the available columns in table 'oed_group_of_exercises':
 * @property integer $id
 * @property string $name
 * @property integer $test
 */
class GroupOfExercises extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_group_of_exercises';
	}
        
        public static $typeGroup = array(
            '1' => 'Упражнение',
            '2' => 'Тест',
        );

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'required'),
			array('type, id_course', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Exercises'=>array(self::MANY_MANY, 'Exercises', 'oed_group_and_exercises(id_group, id_exercise)', 'order'=>'Exercises_Exercises.order ASC'),
                    'PartsOfTest' => array(self::HAS_MANY, 'PartsOfTest', 'id_group', 'order'=>'PartsOfTest.order ASC'),
                    'Lessons' => array(self::MANY_MANY, 'Lessons', 'oed_lesson_and_exercise_group(id_group_exercises, id_lesson)'),
                    'GroupAndSkills'=>array(self::HAS_MANY, 'GroupExerciseAndSkills', 'id_group'),
                    'Skills'=> array(self::MANY_MANY, 'Skills', 'oed_group_exercise_and_skills(id_group, id_skill)'),
                );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nameType' => 'Тип блока',
			'type' => 'Тип',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GroupOfExercises the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function getCountExercises() {
            if($this->type == 1)
                return GroupAndExercises::model()->countByAttributes(array('id_group'=>$this->id));
            else {
               $count = 0;
               foreach($this->PartsOfTest as $part)
               {
                   $count += $part->CountExercises;
               }
               return $count;
            }
        }
        
        public function getTest() {
            return $this->type == 1 ? 'Нет' : 'Да';
        }
        
        public function getExercisesTest() {
            $exercises = array();
            foreach($this->PartsOfTest as $part)
                $exercises = array_merge($exercises, $part->Exercises);
            return $exercises;
        }
        
        public function getNameType() {
            return self::$typeGroup[$this->type];
        }
        
        public function Progress($id_user_and_lesson) {
            $exerciseGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_user_and_lesson'=>$id_user_and_lesson, 'id_exercise_group'=>$this->id));
            if($exerciseGroup->number_all == 0)
                return 0;
            return round($exerciseGroup->number_right/$exerciseGroup->number_all * 100);
        }
             
        public function getHtmlSkills() {
            $res = '<table>';
                if($this->GroupAndSkills)
                {
                    foreach($this->GroupAndSkills as $groupSkills)
                    {
                        $res .= '<td>'. CHtml::dropDownList(
                                    "GroupOfExercises[$this->id][Skills][$groupSkills->id_skill][pass_percent]",
                                    $groupSkills->pass_percent,
                                    LessonAndSkills::getListDataPercents(),
                                    array('id'=>false, 'empty'=>'Выберите процент', 'class'=>'form-control input-sm', 'data-id'=>$groupSkills->id_skill, 'style'=>'background-color:#'.Courses::$bgColors[Skills::$number[$groupSkills->id_skill]].';')
                                ).'</td>';
                    }
                } else {
                    $res .= '<td>нет умений</td>';
                }
            $res .= '</tr></table>';
            return $res;
        }

        public function getHtmlForCourse($active = false) {
            return "
                <tr class='block ".($active ? "active-block" : "")."' data-id='$this->id'>
                    <td class='block-name'>".CHtml::textField("GroupOfExercises[$this->id][name]", $this->name, array('id'=>false, 'class'=>'form-control input-sm', 'placeholder'=>'Введите название урока'))."</td>
                    <td class='block-type'>".CHtml::dropDownList("GroupOfExercises[$this->id][type]", $this->type, GroupOfExercises::$typeGroup, array('id'=>false, 'class'=>'form-control input-sm'))."</td>
                    <td class='block-count'>$this->CountExercises</td>
                    <td class='block-skills'>$this->htmlSkills</td>
                    <td class='block-operation'>
                        ".CHtml::link("<i class='glyphicon glyphicon-pencil'></i>", Yii::app()->createUrl("/admin/groupofexercises/update", array("id"=>$this->id)), array('class'=>'edit'))."
                        ".CHtml::link("<i class='glyphicon glyphicon glyphicon-remove'></i>", Yii::app()->createUrl("/admin/groupofexercises/delete", array("id"=>$this->id)), array('class'=>'remove'))."
                    </td>
                </tr>";
        }
        
        public function afterDelete() {
            
            $usersExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$this->id));
            foreach($usersExerciseGroups as $usersExerciseGroup)
            {
                $usersExerciseGroup->delete();
            }
            
            foreach($this->PartsOfTest as $part)
            {
                $part->delete();
            }
            
            GroupAndExercises::model()->deleteAllByAttributes(array('id_group'=>$this->id));
            GroupExerciseAndSkills::model()->deleteAllByAttributes(array('id_group'=>$this->id));
            LessonAndExerciseGroup::model()->deleteAllByAttributes(array('id_group_exercises'=>$this->id));
            CoursesAndGroupExercise::model()->deleteAllByAttributes(array('id_group'=>$this->id));
            
            parent::afterDelete();
        }
        
        public function getIdsUsedSkills() {
            $ids = array();
            foreach($this->Skills as $skill)
            {
                $ids[] = $skill->id;
            }
            return $ids;
        }
        
        public function percentBySkill($id_skill)
        {
            $model = GroupExerciseAndSkills::model()->findByAttributes(array('id_group'=>$this->id, 'id_skill'=>$id_skill));
            if($model)
            {
                return $model->pass_percent;
            }
            return false;
        }
}
