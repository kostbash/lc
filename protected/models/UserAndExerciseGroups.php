<?php

/**
 * This is the model class for table "oed_user_and_exercise_groups".
 *
 * The followings are the available columns in table 'oed_user_and_exercise_groups':
 * @property integer $id
 * @property integer $id_user_and_lesson
 * @property integer $id_exercise_group
 */
class UserAndExerciseGroups extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_user_and_exercise_groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user_and_lesson, id_exercise_group', 'required'),
			array('id_user_and_lesson, id_exercise_group, number_all, number_right', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user_and_lesson, id_exercise_group', 'safe', 'on'=>'search'),
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
                    'UserAndLesson'=>array(self::BELONGS_TO, 'UserAndLessons', 'id_user_and_lesson'),
                    'Group'=>array(self::BELONGS_TO, 'GroupOfExercises', 'id_exercise_group'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user_and_lesson' => 'Id User And Lesson',
			'id_exercise_group' => 'Id Exercise Group',
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
		$criteria->compare('id_user_and_lesson',$this->id_user_and_lesson);
		$criteria->compare('id_exercise_group',$this->id_exercise_group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OedUserAndExerciseGroups the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        // сохраняем ответы для блока
        public function saveResultBlock($answers=null)
        {
            echo 5;
            $countRight = 0;
            if($answers && is_array($answers))
            {
                foreach($answers as $id_exercise => $attr)
                {
                    if(Exercises::isRightAnswer($id_exercise, $attr['answers']))
                        ++$countRight;
                }
            }

            $this->number_right = $countRight;
            $this->passed = 1;
            $this->number_all = count($answers);
            if($this->save())
            {
                echo 1;
            }
            else
            {
                print_r($this->errors);
                echo 2;
                die;
            }
        }
        
        // сохраняем ответы для теста
        public function saveResultTest(array $answers, $exercises=null)
        {
            $result = array();
            if($exercises && is_array($exercises))
            {
                $countRight = 0;
                $numberAllSkills = array();
                $numberRightAnswerSkills = array();

                foreach($exercises as $key => $id_exercise)
                {
                    $rightAnswer = Exercises::isRightAnswer($id_exercise, $answers[$key]['answers']);
                    if($rightAnswer)
                        ++$countRight;
                    
                    foreach($this->Group->Skills as $skill)
                    {
                        $exerciseHasSkill = ExerciseAndSkills::model()->exists('id_exercise=:id_exercise AND id_skill=:id_skill', array('id_exercise'=>$id_exercise,'id_skill'=>$skill->id));

                        // общее кол-во заданий имеющий данный скилл
                        $numberAllSkills[$skill->id] += (int) $exerciseHasSkill;
                        
                        // кол-во правильных ответов заданий по скиллам
                        if($exerciseHasSkill && $rightAnswer)
                        {
                            $numberRightAnswerSkills[$skill->id] += (int) $rightAnswer;
                        }
                    }
                }
                
                $this->number_right = $countRight;
                $this->number_all = count($exercises);

                $testPassed = 1;
                $resultTest = array();
                
                foreach($numberAllSkills as $id_skill => $numberSkill)
                {
                    $skill = Skills::model()->findByPk($id_skill);
                    $resultTest[$id_skill]['achieved'] = $numberSkill ? round(($numberRightAnswerSkills[$id_skill]/$numberSkill)*100, 0, PHP_ROUND_HALF_DOWN) : 0;
                    $resultTest[$id_skill]['need']= $this->Group->percentBySkill($id_skill) * 100;
                    $resultTest[$id_skill]['skill'] = $skill;
                    if($resultTest[$id_skill]['need'] > $resultTest[$id_skill]['achieved'])
                    {
                        $testPassed = 0;
                    }
                }

                if(!$this->passed)
                {
                    $this->passed = $testPassed;
                }
                
                $this->save(false);
                
                foreach($numberRightAnswerSkills as $skill_id => $numberRight)
                {
                    $userGroupSkills = UserExerciseGroupSkills::model()->findByAttributes(array('id_user_and_lesson'=>$this->UserAndLesson->id, 'id_test_group'=>$this->id_exercise_group, 'id_skill'=>$skill_id));
                    if(!$userGroupSkills)
                    {
                        $userGroupSkills = new UserExerciseGroupSkills;
                        $userGroupSkills->id_user_and_lesson = $this->UserAndLesson->id;
                        $userGroupSkills->id_test_group = $this->id_exercise_group;
                        $userGroupSkills->id_skill = $skill_id;
                        $userGroupSkills->number_all = $numberAllSkills[$skill_id];
                        $userGroupSkills->right_answers = $numberRight;

                    } else {
                        
                        if($numberAllSkills[$skill_id] && $userGroupSkills->number_all && $numberRight/$numberAllSkills[$skill_id] > $userGroupSkills->right_answers/$userGroupSkills->number_all)
                        {
                            $userGroupSkills->number_all = $numberAllSkills[$skill_id];
                            $userGroupSkills->right_answers = $numberRightAnswerSkills[$skill_id];
                        }
                    }
                    $userGroupSkills->save(false);
                }
                
                $result['resultTest'] = $resultTest;
                
                Users::saveAchievements();
            }
            return $result;
        }
        
        public static function ExistUserAndGroup($id_user_and_lesson, $id_group)
        {
            return UserAndExerciseGroups::model()->exists('`id_user_and_lesson`=:id_user_and_lesson AND `id_exercise_group`=:id_group', array('id_user_and_lesson'=>$id_user_and_lesson, 'id_group'=>$id_group));
        }
        
        public function getNextGroup() {
            $lessonExerciseGroup = LessonAndExerciseGroup::model()->findByAttributes(array('id_lesson'=>$this->UserAndLesson->id_lesson, 'id_group_exercises'=>$this->id_exercise_group));
            return LessonAndExerciseGroup::model()->find('`order` > :order AND `id_lesson`=:id_lesson ORDER BY `order` ASC', array('order'=>$lessonExerciseGroup->order, 'id_lesson'=>$lessonExerciseGroup->id_lesson));
        }
        
        public function afterDelete() {
            UserAndExercises::model()->deleteAllByAttributes(array('id_relation'=>$this->id));
            UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_test_group'=>$this->id_exercise_group));
            parent::afterDelete();
        }
        
        public function getNextButton($tab=-1)
        {
            $attrs = array('class'=>'btn btn-success btn-icon-right nextGroup');
            $tab = (int) $tab;
            if($tab >= 0)
            {
                $attrs['tabindex'] = $tab;
            }
            if($this->nextGroup)
            {
                return CHtml::link('Перейти к следующей группе заданий<i class="glyphicon glyphicon-arrow-right"></i>', array('lessons/nextgroup', 'id'=>$this->id), $attrs);
            }
            elseif($this->UserAndLesson->Course->nextLesson($this->UserAndLesson->id_group, $this->UserAndLesson->id_lesson)) 
            {
                return CHtml::link('Следующий урок<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$this->id_user_and_lesson), $attrs);
            } else {
                return CHtml::link('Завершить курс<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$this->id_user_and_lesson), $attrs);
            }
        }
        
        public function getNextLink()
        {
            if($this->nextGroup)
            {
                $link = array('lessons/nextgroup', 'id'=>$this->id);
            }
            elseif($this->UserAndLesson->Course->nextLesson($this->UserAndLesson->id_group, $this->UserAndLesson->id_lesson)) 
            {
                $link = array('courses/nextlesson', 'id_user_lesson'=>$this->id_user_and_lesson);
            } else {
                $link = '/';
            }
            return $link;
        }
}
