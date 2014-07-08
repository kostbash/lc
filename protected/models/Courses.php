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

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description', 'safe', 'on'=>'search'),
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
                    'CourseAndGroupLesson' => array(self::HAS_MANY, 'CourseAndLessonGroup','id_course'),
                    'LessonsGroups' => array(self::MANY_MANY, 'GroupOfLessons', 'oed_course_and_lesson_group(id_course, id_group_lesson)', 'order'=>'LessonsGroups_LessonsGroups.order ASC'),
                    'User' => array(self::MANY_MANY, 'Users', 'oed_courses_and_users(id_course, id_user)'),
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
			'description' => 'Описание',
                        'countLessons' => 'Число уроков',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);

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
                $criteria->compare('User.id', Yii::app()->user->id);
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
        
        public function getCountLessons() {
            $mass = array();
            foreach($this->LessonsGroups as $group)
                $mass[] = $group->id;
            return GroupAndLessons::model()->countByAttributes(array('id_group'=>$mass));
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
        
        public function getProgress() {
            $countLessonUser = UserAndLessons::model()->countByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$this->id, 'passed'=>1));
            if(!$this->countLessons)
                return 0;
            return round($countLessonUser/$this->countLessons * 100);
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
           return CHtml::link($text, array('lessons/pass', 'id'=>$lastLesson->id), array('class'=>'btn btn-success btn-sm'));
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
                 
}
