<?php

/**
 * This is the model class for table "oed_user_and_lessons".
 *
 * The followings are the available columns in table 'oed_user_and_lessons':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_relation
 */
class UserAndLessons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_user_and_lessons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user', 'required'),
			array('id_user', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
                    'Course' => array(self::BELONGS_TO, 'Courses', 'id_course'),
                    'LessonGroup' => array(self::BELONGS_TO, 'GroupOfLessons', 'id_group'),
                    'Lesson' => array(self::BELONGS_TO, 'Lessons', 'id_lesson'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
		);
	}

	public function search()
	{
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('id_user',$this->id_user);
            $criteria->compare('id_group',$this->id_group);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function existLesson($id_course, $id_group, $id_lesson) {
            $model = UserAndLessons::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$id_course, 'id_group'=>$id_group, 'id_lesson'=>$id_lesson));
            return $model ? $model : false;
        }
             
        public function getLessonProgress() {
            $countExerciseUser = UserAndExerciseGroups::model()->countByAttributes(array('id_user_and_lesson'=>$this->id, 'passed'=>1));
            $lessonExercises = LessonAndExerciseGroup::model()->countByAttributes(array('id_lesson'=>$this->id_lesson));
            if(!$lessonExercises)
                return 0;
            return round($countExerciseUser/$lessonExercises * 100);
        }
        
        public function getRepeatLesson() {
            if(!$this->Lesson->ExercisesGroups)
                return false;
            $tests = 0;
            foreach($this->Lesson->ExercisesGroups as $exerciseGroup) {
                if($exerciseGroup->type == 2)
                {
                    ++$tests;
                    $userExerciseGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_user_and_lesson'=>$this->id, 'id_exercise_group'=>$exerciseGroup->id));
                    if(!$userExerciseGroup)
                        return false;
                    if($userExerciseGroup->passed == 0)
                        return false;
                }
            }
            if(!$tests)
                return false;
            return true;
        }
        
        public function getPosition() {
            $courseGroups = CourseAndLessonGroup::model()->findAll("`id_course`=$this->id_course ORDER BY `order` ASC");
            $user = Users::model()->findByPk(Yii::app()->user->id);
            $count = $user->type==1 ? 0 : 1;
            foreach($courseGroups as $courseGroup) {
                foreach($courseGroup->GroupAndLessons as $key => $lessonGroup)
                {
                    if($key==0 && $count==1)
                        continue;
                    if($lessonGroup->id_group == $this->id_group && $lessonGroup->id_lesson == $this->id_lesson)
                        break 2;
                    ++$count;
                }
            }
            return $count;
        }
}
