<?php

/**
 * This is the model class for table "oed_courses".
 *
 * The followings are the available columns in table 'oed_courses':
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class Courses extends CActiveRecord
{
        public static $defaultCourse = 11;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_courses';
	}
        
        public static $bgColors = array(
            0 => 'f00',
            1 => '0f0',
            2 => 'cfa',
            3 => 'eee',
            4 => 'a14',
            5 => 'eda',
            6 => 'd6e',
            7 => '3a6',
            8 => 'a89',
            9 => 'a32',
        );
        
        public static $difficulties = array(
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
        );

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name, learning_time', 'length', 'max'=>255),
			array('description', 'safe'),
                        array('change_date', 'date', 'format'=>'yyyy-mm-dd hh:mm:ss'),
                        array('difficulty, id_subject, id_class', 'numerical'),
                        array('id_editor', 'numerical', 'on'=>'create'),
			array('id, name, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            return array(
                'CourseAndGroupLesson' => array(self::HAS_MANY, 'CourseAndLessonGroup','id_course'),
                'LessonsGroups' => array(self::MANY_MANY, 'GroupOfLessons', 'oed_course_and_lesson_group(id_course, id_group_lesson)', 'order'=>'LessonsGroups_LessonsGroups.order ASC'),
                'Users' => array(self::MANY_MANY, 'Users', 'oed_courses_and_users(id_course, id_user)'),
                'Skills' => array(self::MANY_MANY, 'Skills', 'oed_course_and_skills(id_course, id_skill)'),
                'Lessons'=>array(self::MANY_MANY, 'Lessons', 'oed_courses_and_lessons(id_course, id_lesson)', 'order'=>'Lessons_Lessons.order ASC'),
                'Blocks'=>array(self::MANY_MANY, 'GroupOfExercises', 'oed_courses_and_group_exercise(id_course, id_group)', 'order'=>'Blocks_Blocks.order ASC'),
                'CoursesAndGroupExercise'=>array(self::HAS_MANY, 'CoursesAndGroupExercise', 'id_course', 'order'=>'CoursesAndGroupExercise.order'),
                'CoursesAndLessons'=>array(self::HAS_MANY, 'CoursesAndLessons', 'id_course', 'order'=>'CoursesAndLessons.order'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                    'id' => 'ID',
                    'name' => 'Название',
                    'difficulty' => 'Сложность',
                    'id_subject' => 'Предмет',
                    'id_class' => 'Класс',
                    'description' => 'Описание',
                    'countLessons' => 'Число уроков',
                    'learning_time' => 'Предполагаемое время обучения',
		);
	}

	public function search()
	{
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('description',$this->description, true);
            if(!Yii::app()->user->checkAccess('admin'))
            {
                $criteria->compare('id_editor', Yii::app()->user->id);
            }
            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
	}
        
	public function searchUserCourses()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
                $criteria->with = array('User');
                $criteria->together = true;
                $criteria->compare('Users.id', Yii::app()->user->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getIdsUsedSkills() {
            $ids = array();
            foreach($this->Skills as $skill)
            {
                $ids[] = $skill->id;
            }
            return $ids;
        }
        
        public function getCourseSkills() {
            $res = array();
            foreach($this->LessonsGroups as $lessonGroup)
                foreach($lessonGroup->LessonsRaw as $lesson)
                    foreach($lesson->Skills as $skill) {
                        if(!array_key_exists($skill->id, $res))
                            $res[$skill->id] = $skill;
                    }
            return $res;
        }
        
        public function getCountPassedLessons() {
            return UserAndLessons::model()->countByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$this->id, 'passed'=>1));
        }
        
        public function getCountLessons() {
            $mass = array();
            foreach($this->LessonsGroups as $group)
                $mass[] = $group->id;
            return GroupAndLessons::model()->countByAttributes(array('id_group'=>$mass));
        }
        
        public function getCountBlocks($type=1)
        {
            $type = (int) $type;
            $queryThemes = "SELECT id_group_lesson FROM `oed_course_and_lesson_group` WHERE `id_course`=$this->id ORDER BY `order` ASC";
            $themes = Yii::app()->db->createCommand($queryThemes)->queryAll();
            $themesIds = array();
            foreach($themes as $n => $theme)
            {
                if($n == 0)
                    continue; // убераем проверочный тест
                $themesIds[] = $theme['id_group_lesson'];
            }
            if(!$themesIds)
                return 0;
                $themesIds = implode(',', $themesIds);
            
            $queryLessons = "SELECT id_lesson FROM `oed_group_and_lessons` WHERE `id_group` IN($themesIds)";
            $lessons = Yii::app()->db->createCommand($queryLessons)->queryAll();
            $lessonsIds = array();
            foreach($lessons as $lesson)
            {
                $lessonsIds[] = $lesson['id_lesson'];
            }
            $lessonsIds = implode(',', $lessonsIds);
            if(!$lessonsIds)
                return 0;
            $queryCount = "SELECT COUNT(*) as count FROM `oed_lesson_and_exercise_group` lb, `oed_group_of_exercises` b WHERE lb.id_group_exercises=b.id AND lb.id_lesson IN($lessonsIds) AND b.type=$type";
            $count = Yii::app()->db->createCommand($queryCount)->queryAll();
            return $count[0]['count'];
        }
        
        public function getThemesLessons()
        {
            $lessons = array();
            foreach($this->LessonsGroups as $theme)
            {
                $lessons = array_merge($lessons, $theme->LessonsRaw);
            }
            return $lessons;
        }
        
        public function getAverageByTests() {
            $userLessons = UserAndLessons::model()->findAllByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$this->id));
            $userLessonsIds = array();
            $number_all = 0;
            $number_right = 0;
            foreach($userLessons as $userLesson)
            {
                $userLessonsIds[] = $userLesson->id;
            }
            
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id_user_and_lesson', $userLessonsIds);
            $criteria->with = array('Group');
            $criteria->compare('Group.type', 2);
            
            $resultTests = UserAndExerciseGroups::model()->findAll($criteria);
            foreach($resultTests as $resultTest)
            {
                $number_all += $resultTest->number_all;
                $number_right += $resultTest->number_right;
            }
            
            return $number_all==0 ? 0 : round($number_right/$number_all * 100);
        }
        
        public function getProgress() {
            if(!$this->countLessons)
                return 0;
            return round($this->countPassedLessons/$this->countLessons * 100);
        }
        
        public function nextLesson($id_group, $id_lesson) {
            $result = array();
            foreach($this->LessonsGroups as $keyGroup => $lessonGroup)
                foreach($lessonGroup->LessonsRaw as $keyLesson => $lesson)
                    if($id_group == $lessonGroup->id && $id_lesson == $lesson->id)
                    {
                        if($lessonGroup->LessonsRaw[$keyLesson+1]) {
                            $result['id_group'] = $lessonGroup->id;
                            $result['id_lesson'] = $lessonGroup->LessonsRaw[$keyLesson+1]->id;
                        } elseif($this->LessonsGroups[$keyGroup+1] && $this->LessonsGroups[$keyGroup+1]->LessonsRaw[0]) {
                            $result['id_group'] = $this->LessonsGroups[$keyGroup+1]->id;
                            $result['id_lesson'] = $this->LessonsGroups[$keyGroup+1]->LessonsRaw[0]->id;
                        }
                        break 2;
                    }
            return $result;
        }
        
        public function getHasUserCourse() {
            if(CoursesAndUsers::model()->findByAttributes(array('id_course'=>$this->id, 'id_user'=>Yii::app()->user->id)))
                    return true;
            return false;
        }

        public function stateButton() {
            $lastLesson = $this->lastUserLesson;
            $number = $lastLesson->position;
            $exerciseGroup = UserAndExerciseGroups::model()->exists('id_user_and_lesson=:id_user_and_lesson AND passed=:passed', array('id_user_and_lesson'=>$lastLesson->id, 'passed'=>1));
            if($exerciseGroup)
            {
                $text = "Продолжить урок $number";
            }
            else
            {
                $key = Users::UserType() == 1 ? 0 : 1;
                if($lastLesson->id_lesson == $this->LessonsGroups[0]->LessonsRaw[$key]->id)
                  $text = 'Начать первый урок';
                else
                  $text = "Начать урок $number";
            }
            if($lastLesson)
                return CHtml::link($text, array('lessons/pass', 'id'=>$lastLesson->id), array('class'=>'course-state-button'));
            return false;
        }
        
        // последний урок, до которого дошел пользователь
        public function getLastUserLesson() {
            foreach($this->LessonsGroups as $groupKey => $lessonGroup)
                foreach($lessonGroup->LessonsRaw as $lessonKey => $lesson)
                {
                    if($groupKey == 0 && $lessonKey == 0 && Users::UserType() != 1)
                        continue;
                    $lesson = UserAndLessons::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$this->id, 'id_group'=>$lessonGroup->id, 'id_lesson'=>$lesson->id));
                    if($lesson)
                        $lastLesson = $lesson;
                    else
                        break 2;
                }
            if($lastLesson)
                return $lastLesson;
            return null;
        }
        
        public function afterDelete() {
            
            $courseSkills = Skills::model()->findAllByAttributes(array('id_course'=>$this->id));
            foreach($courseSkills as $skill)
            {
                $skill->delete();
            }
            CourseAndSkills::model()->deleteAllByAttributes(array('id_course'=>$this->id));
            
            foreach($this->Blocks as $block)
            {
                $block->delete();
            }
            
            CoursesAndGroupExercise::model()->deleteAllByAttributes(array('id_course'=>$this->id));
            
            foreach($this->Lessons as $lessonCourse)
            {
                $lessonCourse->delete();
            }
            
            foreach($this->LessonsGroups as $lessonGroup)
            {
                foreach($lessonGroup->LessonsRaw as $lesson)
                {
                    $lesson->delete();
                }
                $lessonGroup->delete();
            }
            CourseAndLessonGroup::model()->deleteAllByAttributes(array('id_course'=>$this->id));
            
            CoursesAndUsers::model()->deleteAllByAttributes(array('id_course'=>$this->id));
            
            parent::afterDelete();
        }
        
        public function changeDate()
        {
            $this->change_date = date('Y-m-d H:i:s');
            $this->save(false);
        }
        
        public static function CourseById($id)
        {
            $id = (int) $id;
            if(Yii::app()->user->checkAccess('admin'))
                return Courses::model()->findByPk($id);
            return Courses::model()->findByAttributes(array('id'=>$id, 'id_editor'=>Yii::app()->user->id));
        }
        
        public static function existCourseById($id)
        {
            $id = (int) $id;
            if(Yii::app()->user->checkAccess('admin'))
                return Courses::model()->exists('`id`=:id', array('id'=>$id));
            return Courses::model()->exists('`id`=:id AND id_editor=:id_editor', array('id'=>$id, 'id_editor'=>Yii::app()->user->id));
        }
        
        public function getClassName()
        {
            $class = CourseClasses::model()->findByPk($this->id_class);
            if($class)
                return $class->name;
            else
                return '*';
        }
        
        public function getUserStatus()
        {
            $lastActive = CoursesAndUsers::model()->find(array(
                'condition'=> 'id_user=:id_user',
                'params'=>array('id_user'=>Yii::app()->user->id),
                'order'=>'`activity_date` DESC',
            ));
            if($lastActive && $lastActive->id_course == $this->id)
                return "<div class='last-active'>ПОСЛЕДНИЙ АКТИВНЫЙ</div>";
            $userAndCourse = CoursesAndUsers::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$this->id));
            if($userAndCourse)
            {
                if($userAndCourse->status==1)
                    return "<div class='active'>$this->progress <span class='percent'>%</span></div>";
                if($userAndCourse->status==2)
                    return "<div class='passed'>ПРОЙДЕНО!</div>";
            }
            return "<div class='not-active'>НЕ ПРИСТУПАЛ</div>";
        }
                 
}
