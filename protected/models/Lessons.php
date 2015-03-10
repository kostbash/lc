<?php

/**
 * This is the model class for table "oed_lessons".
 *
 * The followings are the available columns in table 'oed_lessons':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $theory
 */
class Lessons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_lessons';
	}

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
                        array('course_creator_id', 'numerical', 'integerOnly'=>true),
			array('description, theory', 'safe'),
                        array('change_date', 'date', 'format'=>'yyyy-mm-dd hh:mm:ss'),
			array('id, name, description, theory', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    //"Skills" => array(self::MANY_MANY, "Skills", 'oed_lesson_and_skills(id_lesson, id_skill)'),
                    //'LessonAndSkills' => array(self::HAS_MANY, 'LessonAndSkills', 'id_lesson'),
                    'CourseCreator'=>array(self::BELONGS_TO, 'Courses', 'course_creator_id'),
                    "Groups" => array(self::MANY_MANY, "GroupOfLessons", 'oed_group_and_lessons(id_lesson, id_group)'),
                    "ExercisesGroups" => array(self::MANY_MANY, 'GroupOfExercises', 'oed_lesson_and_exercise_group(id_lesson, id_group_exercises)', 'order'=>'ExercisesGroups_ExercisesGroups.order ASC'),
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
			'theory' => 'Теоретическая часть',
                        'skillsString' => 'Умения',
                        'coursesString' => 'Курсы',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('theory',$this->theory,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function getIdsUsedSkills() {
            $ids = array();
            foreach($this->Skills as $skill)
            {
                $ids[] = $skill->id;
            }
            return $ids;
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function canSaveFromCourse($id_course) {
            if($this->course_creator_id != $id_course)
                return;
            $lessonsGroups = GroupAndLessons::model()->findAllByAttributes(array('id_lesson'=>$this->id));
            if(count($lessonsGroups) > 1)
            {
                foreach($lessonsGroups as $lessonGroup)
                {
                    if($lessonGroup->Course->id !== $id_course)
                        return;
                }
            }
            return true;
        }
        
        public function getSkillsString() {
            $res = array();
            foreach($this->Skills as $skill)
            {
                $res[] = $skill->name;
            }
            return implode(', ', $res);
        }
        
        public function getCoursesString() {
            $res = array();
            foreach($this->Groups as $group) 
            {
                foreach($group->Courses as $course)
                {
                    if(!array_key_exists($course->id, $res))
                        $res[$course->id] = $course->name;
                }
            }
            return implode(', ', $res);
        }
        
        public function getCanDelete() {
            if($this->Groups)
                return 0;
            return 1;
        }
        
        public function getNumberExercisesCriteria() {
            $numberExercisesSkills = array();
            foreach($this->ExercisesGroups as $exericiseGroup)
            {
                foreach($exericiseGroup->ExercisesByCriteria as $exercise)
                {
                    foreach($this->Skills as $skill)
                    {
                        $numberExercisesSkills[$skill->id] +=ExerciseAndSkills::model()->exists('id_exercise=:id_exercise AND id_skill=:id_skill', array('id_exercise'=>$exercise->id,'id_skill'=>$skill->id));
                    }
                }
            }
            return $numberExercisesSkills;
        }
        
        public static function ProgressSkill($id_user_and_lesson, $id_skill) {
            $exerciseGroupSkills = UserExerciseGroupSkills::model()->findAllByAttributes(array('id_user_and_lesson'=>$id_user_and_lesson,'id_skill'=>$id_skill));
            //$userAndLesson = UserAndLessons::model()->findByPk($id_user_and_lesson);
            $sumRight = 0;
            foreach($exerciseGroupSkills as $exerciseGroupSkill) {
                $sumRight +=$exerciseGroupSkill->right_answers;
                $numberExercisesSkill += $exerciseGroupSkill->number_all;
            }
            
            if(!$numberExercisesSkill)
                return 0;
            return round($sumRight/$numberExercisesSkill * 100);
        }
        
        public static function PercentNeedBySkill($id_lesson, $id_skill)
        {
            $model = Lessons::model()->findByPk($id_lesson);
            if($model)
                $skill = $model->LessonAndSkills[$id_skill];
            if($skill)
                return $skill->pass_percent * 100;
        }
        
        public function AccessNextLesson($id_user_and_lesson) {
            $userLesson = UserAndLessons::model()->findByPk($id_user_and_lesson);
            if($userLesson)
            {
                $access = $userLesson->saveLessonPassed();
                if(!$userLesson->Course->nextLesson($userLesson->id_group, $userLesson->id_lesson))
                {
                    $access=false;
                }
                
                return $access;
            }
        }
        
        public static function PercentRightTests($id_course, $id_group, $id_lesson) {
            $userLesson = UserAndLessons::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$id_course, 'id_group'=>$id_group, 'id_lesson'=>$id_lesson));
            if($userLesson) {
                $userExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                $right = 0;
                $all = 0;
                foreach($userExerciseGroups as $userExerciseGroup) {
                    if($userExerciseGroup->Group->type == 2) {
                        $right += $userExerciseGroup->number_right;
                        $all += $userExerciseGroup->number_all;
                    }
                }
                if(!$all)
                    return 0;
                return round($right/$all * 100, 0, PHP_ROUND_HALF_DOWN);
            }
        }
        
        public static function ResultCheck($right, $all)
        {
            $result = array();
            $percent = $right==0 ? 0 : round($right/$all, 1) * 100;
            if($percent > 90)
            {
                $result = array('mark'=>'Вы отлично справились с заданием!', 'recommendation'=>'Вы совершенного готовы приступать к прохождению курса - он будет Вам полезен!');
            }
            elseif($percent >= 50 && $percent <=90)
            {
                $result = array('mark'=>'Вы хорошо справились с заданием!', 'recommendation'=>'Вы можете приступить к курсу, но обратите особое внимание на первые уроки');
            }
            else
            {
                $result = array('mark'=>'Навык счета нуждается в повторонии', 'recommendation'=>'Пройдите курс');
            }
            
            return $result;
        }
        
        public function getHtmlForCourse($active = false) {
            
            $exerciseGroupHtml = "<table class='blocks' data-id='$this->id'><tbody>";
            foreach($this->ExercisesGroups as $exercisesGroup)
            {
                $exerciseGroupHtml .= $exercisesGroup->getHtmlForCourse($active);
            }
            $exerciseGroupHtml .= '</tbody></table>';
            
            $lessonHtml = "
            <tr class='lesson' data-id='$this->id' >
                    <td class='lesson-name clearfix'>
                        ".CHtml::textArea("Lessons[$this->id][name]", $this->name, array('id'=>false, 'class'=>'form-control'))."
                        <div class='lesson-remove'>".CHtml::link('<i class="glyphicon glyphicon-remove"></i>', '#')."</div>
                        <h3 class='lessons_count' style='position: relative; right: 607%; bottom: 20px;'></h3>
                    </td>
            </tr>";
            $result['exerciseGroupHtml'] = $exerciseGroupHtml;
            $result['lessonHtml'] = $lessonHtml;
            return $result;
        }
        
        public function getSkills() {
            $skills = array();
            foreach($this->ExercisesGroups as $exerciseGroup)
            {
                if($exerciseGroup->type==2)
                {
                    foreach($exerciseGroup->Skills as $skill)
                    {
                        if(!$skills[$skill->id])
                            $skills[$skill->id] = $skill;
                    }
                }
            }
            return $skills;
        }
        
        public function getLessonAndSkills() {
            $skills = array();
            foreach($this->ExercisesGroups as $exerciseGroup)
            {
                if($exerciseGroup->type==2)
                {
                    foreach($exerciseGroup->GroupAndSkills as $groupSkill)
                    {
                        if(!$skills[$groupSkill->id_skill] or $groupSkill->pass_percent < $skills[$groupSkill->id_skill]->pass_percent)
                            $skills[$groupSkill->id_skill] = $groupSkill;
                    }
                }
            }
            return $skills;
        }
        
        public function afterDelete() {
            foreach($this->ExercisesGroups as $group)
            {
                $group->delete();
            }
            
            CoursesAndLessons::model()->deleteAllByAttributes(array('id_lesson'=>$this->id));
            GroupAndLessons::model()->deleteAllByAttributes(array('id_lesson'=>$this->id));
            LessonAndExerciseGroup::model()->deleteAllByAttributes(array('id_lesson'=>$this->id));
            $userLessons = UserAndLessons::model()->findAllByAttributes(array('id_lesson'=>$this->id));
            foreach($userLessons as $userLesson)
            {
                $userLesson->delete();
            }
            
            parent::afterDelete();
        }
        
        public function changeDate()
        {
            $this->change_date = date('Y-m-d H:i:s');
            $this->save(false);
        }
        
        protected function afterSave() {
            if($course = $this->CourseCreator)
            {
                $course->changeDate();
            }
            parent::afterSave();
        }
        
        public function getPosition()
        {
            $position = 0;
            $query = "SELECT lessons.id_lesson FROM `oed_courses` course, `oed_course_and_lesson_group` themes, `oed_group_and_lessons` lessons
                    WHERE themes.id_course=course.id AND lessons.id_group=themes.id_group_lesson AND course.id='$this->course_creator_id'
                    ORDER BY themes.order ASC, lessons.order ASC";
            $lessons = Yii::app()->db->createCommand($query)->queryAll();
            
            foreach($lessons as $lesson)
            {
                if($lesson['id_lesson'] == $this->id)
                {
                    break;
                }
                ++$position;
                
            }
            return $position;
        }
        
        public function getIdsBlocks()
        {
            $query = "SELECT id_group_exercises as id_block FROM `oed_lesson_and_exercise_group` WHERE id_lesson=$this->id ORDER BY `order` ASC";
            $blocks = Yii::app()->db->createCommand($query)->queryAll();
            $result = array();
            
            foreach($blocks as $block)
            {
                $result[] = $block['id_block'];
            }
            return $result;
        }
}
