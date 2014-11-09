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
            $countRight = 0;
            $attrForBlockLog = array();
            $attrForBlockLog['duration'] = 0;
            if($answers && is_array($answers))
            {
                foreach($answers as $id_exercise => $attr)
                {
                    $attrForExerciseLog = array();
                    $isRight = Exercises::isRightAnswer($id_exercise, $attr['answers']);
                    if($isRight)
                        ++$countRight;
                    
                    $attrForExerciseLog['id_exercise'] = $id_exercise;
                    $attrForBlockLog['duration'] += $attrForExerciseLog['duration'] = (int) $attr['duration'];
                    $attrForExerciseLog['right'] = (int) $isRight;
                    $this->saveExerciseLog($attrForExerciseLog, $attr['answers']);
                }
            }
            $attrForBlockLog['passed'] =  $this->passed = 1;;
            $this->number_right = $countRight;
            $this->number_all = count($answers);
            $this->save();
            $this->saveBlockLog($attrForBlockLog);
        }
        
        // сохраняем ответы для теста
        public function saveResultTest(array $answers, $exercises=null)
        {
            $result = array();
            $attrForBlockLog = array();
            $attrForBlockLog['duration'] = 0;
//            CVarDumper::dump($answers, 10, true);
//            CVarDumper::dump($exercises, 10, true); die;
            if($exercises && is_array($exercises))
            {
                $countRight = 0;
                $numberAllSkills = array();
                $numberRightAnswerSkills = array();

                foreach($exercises as $key => $id_exercise)
                {
                    $attributesForLogs = array();
                    $rightAnswer = Exercises::isRightAnswer($id_exercise, $answers[$key]['answers']);
                    if($rightAnswer)
                        ++$countRight;
                    
                    $attributesForLogs['id_exercise'] = $id_exercise;
                    $attrForBlockLog['duration'] += $attributesForLogs['duration'] = (int) $answers[$key]['duration'];
                    $attributesForLogs['right'] = (int) $rightAnswer;
                    $this->saveExerciseLog($attributesForLogs, $answers[$key]['answers']);
                    
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
                $resultSkills = array();
                
                foreach($numberAllSkills as $id_skill => $numberSkill)
                {
                    $skill = Skills::model()->findByPk($id_skill);
                    $resultSkills[$id_skill]['achieved'] = $numberSkill ? round(($numberRightAnswerSkills[$id_skill]/$numberSkill)*100, 0, PHP_ROUND_HALF_DOWN) : 0;
                    $resultSkills[$id_skill]['need']= $this->Group->percentBySkill($id_skill) * 100;
                    $resultSkills[$id_skill]['skill'] = $skill;
                    if($resultSkills[$id_skill]['need'] > $resultSkills[$id_skill]['achieved'])
                    {
                        $resultSkills[$id_skill]['passed'] = 0;
                        $testPassed = 0;
                    }
                    else
                    {
                        $resultSkills[$id_skill]['passed'] = 1;
                    }
                }
                
                $attrForBlockLog['passed'] = $result['passed'] = $testPassed;

                if(!$this->passed)
                {
                    $this->passed = $testPassed;
                }
                
                $this->save(false);
                $id_log = $this->saveBlockLog($attrForBlockLog);
                $this->saveBlockLogSkills($id_log, $resultSkills);
                
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
                
                $result['skills'] = $resultSkills;
                $this->saveNotificationPassedTest($result);
                Users::saveAchievements();
            }
            return $result;
        }
        
        public function saveExerciseLog($attributes, $answer)
        {
            $exerciseLog = new UserExercisesLogs;
            $exerciseLog->attributes = $attributes;
            $exerciseLog->id_user = Yii::app()->user->id;
            $exerciseLog->id_course = $this->UserAndLesson->id_course;
            $exerciseLog->id_theme = $this->UserAndLesson->id_group;
            $exerciseLog->id_lesson = $this->UserAndLesson->id_lesson;
            $exerciseLog->id_block = $this->id_exercise_group;
            $exerciseLog->date = date('Y-m-d');
            $exerciseLog->time = date('H:i:s');
            $exerciseLog->answer = serialize($answer);
            if($exerciseLog->save())
            {
                $this->saveExerciseLogForParent($exerciseLog->id);
                $this->saveExerciseLogForTeachers($exerciseLog->id);
            }
        }
        
       
        public function saveExerciseLogForParent($id_log)
        {
            $parent = $this->UserAndLesson->User->parentRelation;
            if($parent)
            {
                $exerciseLogTeacher = new UserExercisesLogsAndTeacher;
                $exerciseLogTeacher->id_log = $id_log;
                $exerciseLogTeacher->id_teacher = $parent->id_parent;
                $exerciseLogTeacher->id_student = $parent->id_child;
                $exerciseLogTeacher->new = 1;
                $exerciseLogTeacher->save();
            }
        }
        
        public function saveExerciseLogForTeachers($id_log)
        {
            $teachers = $this->UserAndLesson->User->teachersRelations;
            if($teachers)
            {
                foreach($teachers as $teacher)
                {
                    $exerciseLogTeacher = new UserExercisesLogsAndTeacher;
                    $exerciseLogTeacher->id_log = $id_log;
                    $exerciseLogTeacher->id_teacher = $teacher->id_teacher;
                    $exerciseLogTeacher->id_student = $teacher->id_student;
                    $exerciseLogTeacher->new = 1;
                    $exerciseLogTeacher->save();
                }
            }
        }
        
        public function saveBlockLogSkills($id_log, array $resultBySkills)
        {
            foreach($resultBySkills as $id_skill => $resultBySkill)
            {
                $blockLogSkill = new UserBlocksLogsSkills;
                $blockLogSkill->id_log = $id_log;
                $blockLogSkill->id_skill = $id_skill;
                $blockLogSkill->achieved_percent = $resultBySkill['achieved'];
                $blockLogSkill->need_percent = $resultBySkill['need'];
                $blockLogSkill->save();
            }
        }
        
        public function saveBlockLog($attributes)
        {
            $blockLog = new UserBlocksLogs;
            $blockLog->attributes = $attributes;
            $blockLog->id_user = Yii::app()->user->id;
            $blockLog->id_course = $this->UserAndLesson->id_course;
            $blockLog->id_theme = $this->UserAndLesson->id_group;
            $blockLog->id_lesson = $this->UserAndLesson->id_lesson;
            $blockLog->id_block = $this->id_exercise_group;
            $blockLog->date = date('Y-m-d');
            $blockLog->time = date('H:i:s');
            if($blockLog->save())
            {
                return $blockLog->id;
            }
        }
        
        public function saveNotificationPassedTest(array $resultTest)
        {
            $notification = new StudentNotifications;
            $notification->id_user = Yii::app()->user->id;
            $notification->id_type = $resultTest['passed'] ? 4 : 3;
            $notification->date = date('Y-m-d');
            $notification->time = date('H:i:s');
            $text .= "<b>Блок: </b>".$this->Group->name.", ";
            $text .= "<b>Урок: </b>".$this->UserAndLesson->Lesson->name.", ";
            $text .= "<b>Курс: </b>".$this->UserAndLesson->Course->name."<br />";
            $text .= "<b>Умения: </b><br />";
            foreach($resultTest['skills'] as $resultSkill)
            {
                $text .= "<i><b>".$resultSkill['skill']->name.": </i></b>".$resultSkill['achieved']."% (".$resultSkill['need']."%)<br />";
            }
            $notification->text = $text;
            if($notification->save())
            {
                $this->saveNotificationPassedTestForParent($notification->id);
                $this->saveNotificationPassedTestForTeachers($notification->id);
            }
        }
        
        public function saveNotificationPassedTestForParent($id_notification)
        {
            $parent = $this->UserAndLesson->User->parentRelation;
            if($parent)
            {
                $notificationTeacher = new StudentNotificationsAndTeacher;
                $notificationTeacher->id_notification = $id_notification;
                $notificationTeacher->id_teacher = $parent->id_parent;
                $notificationTeacher->id_student = $parent->id_child;
                $notificationTeacher->new = 1;
                $notificationTeacher->save();
            }
        }
        
        public function saveNotificationPassedTestForTeachers($id_notification)
        {
            $teachers = $this->UserAndLesson->User->teachersRelations;
            if($teachers)
            {
                foreach($teachers as $teacher)
                {
                    $notificationTeacher = new StudentNotificationsAndTeacher;
                    $notificationTeacher->id_notification = $id_notification;
                    $notificationTeacher->id_teacher = $teacher->id_teacher;
                    $notificationTeacher->id_student = $teacher->id_student;
                    $notificationTeacher->new = 1;
                    $notificationTeacher->save();
                }
            }
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
            $attrs = array('class'=>'next-button');
            $tab = (int) $tab;
            if($tab >= 0)
            {
                $attrs['tabindex'] = $tab;
            }
            if($this->nextGroup)
            {
                return CHtml::link('К следующей группе заданий', array('lessons/nextgroup', 'id'=>$this->id), $attrs);
            }
            elseif($this->UserAndLesson->Course->nextLesson($this->UserAndLesson->id_group, $this->UserAndLesson->id_lesson)) 
            {
                return CHtml::link('К следующему уроку', array('courses/nextlesson', 'id_user_lesson'=>$this->id_user_and_lesson), $attrs);
            } else {
                return CHtml::link('Завершить курс', array('courses/congratulation', 'id'=>$this->UserAndLesson->id_course), $attrs);
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
