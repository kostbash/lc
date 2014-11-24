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
			array('id_user, id_course, id_group, id_lesson, passed', 'required'),
			array('id_user, id_course, id_group, id_lesson, passed', 'numerical', 'integerOnly'=>true),
                        array('last_activity_date', 'date', 'format'=>'yyyy-mm-dd hh:mm:ss'),
			array('id, id_user', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
                    'Course' => array(self::BELONGS_TO, 'Courses', 'id_course'),
                    'LessonGroup' => array(self::BELONGS_TO, 'GroupOfLessons', 'id_group'),
                    'Lesson' => array(self::BELONGS_TO, 'Lessons', 'id_lesson'),
                    'User'=> array(self::BELONGS_TO, 'Users', 'id_user'),
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
        
        public function getLastUserBlock()
        {
            $query = "SELECT userBlock.* FROM `oed_lesson_and_exercise_group` lesBlock, `oed_user_and_exercise_groups` userBlock
                    WHERE lesBlock.id_lesson = :id_lesson AND lesBlock.id_group_exercises = userBlock.id_exercise_group AND userBlock.id_user_and_lesson = :id_user_and_lesson
                    ORDER BY lesBlock.order DESC LIMIT 1";
            return UserAndExerciseGroups::model()->findBySql($query, array('id_lesson'=>$this->id_lesson, 'id_user_and_lesson'=>$this->id));
        }
        
        public function afterDelete() {
            $userExercisesGroup = UserAndExerciseGroups::model()->findAllByAttributes(array('id_user_and_lesson'=>$this->id));
            if($userExercisesGroup)
            {
                foreach($userExercisesGroup as $group)
                {
                    $group->delete();
                }
            }
            parent::afterDelete();
        }
        
        public function OnChangeLesson($makePassed = false)
        {
            $changeDate = strtotime($this->Lesson->change_date);
            $lastActivityDate = strtotime($this->last_activity_date);
            
            if($makePassed || ($changeDate >= $lastActivityDate))
            {
                // если урок пройден, все блоки без исключения должны быть пройдены
                if($this->passed)
                {
                    $passed = true;
                }
                else
                {
                    $passed = false;
                    $lastUserBlock = $this->lastUserBlock;
                }
                
                
                foreach($this->Lesson->idsBlocks as $id_block)
                {
                    if($passed || $lastUserBlock->id_exercise_group != $id_block)
                    {
                        $userExercisesGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_user_and_lesson'=>$this->id, 'id_exercise_group'=>$id_block));
                        if(!$userExercisesGroup)
                        {
                            $userExercisesGroup = new UserAndExerciseGroups;
                            $userExercisesGroup->id_user_and_lesson = $this->id;
                            $userExercisesGroup->id_exercise_group = $id_block;
                            $userExercisesGroup->last_activity_date = date('Y-m-d H:i:s');
                        }
                        
                        $makePassed = false;
                        
                        if($userExercisesGroup->isNewRecord || !$userExercisesGroup->passed)
                        {
                            $userExercisesGroup->passed = 1;
                            $userExercisesGroup->save();
                            $makePassed = true;
                        }
                        
                        $userExercisesGroup->onChangeBlock($makePassed);
                    }
                    else
                    {
                        $lastUserBlock->onChangeBlock();
                        break;
                    }
                }
            }
            $this->last_activity_date = date('Y-m-d H:i:s');
            $this->save();
        }
}
