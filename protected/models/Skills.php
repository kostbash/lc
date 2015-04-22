<?php

/**
 * This is the model class for table "oed_skills".
 *
 * The followings are the available columns in table 'oed_skills':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $condition
 */
class Skills extends CActiveRecord
{
    public static $number;
    public static $cache = array();
    
    public static $SkillsTypes = array(
        '1' => 'Знания',
        '2' => 'Навык',
    );
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_skills';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('condition', 'length', 'max'=>65535),
			array('name, type', 'required'),
			array('name', 'length', 'max'=>255),
            array('n1, n2, n3', 'numerical', 'min'=>0),
			array('id_course, skillsGroup', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, condition', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Exercises'=>array(self::MANY_MANY, 'Exercises', 'oed_exercise_and_skills(id_skill, id_exercise)'),
                    'ExercisesGroups'=>array(self::MANY_MANY, 'GroupExerciseAndSkills', 'oed_group_exercise_and_skills(id_skill, id_group)'),
                    'TopSkills'=>array(self::MANY_MANY, 'Skills', 'oed_relation_skills(id_skill, id_main_skill)'),
                    'UnderSkills'=>array(self::MANY_MANY, 'Skills', 'oed_relation_skills(id_main_skill, id_skill)'),
                    'Courses'=>array(self::MANY_MANY, 'Courses', 'oed_course_and_skills(id_skill, id_course)'),
                    'CourseAndSkills'=>array(self::HAS_MANY, 'CourseAndSkills', 'id_skill'),
                    'SkillsGroups'=>array(self::MANY_MANY, 'SkillsGroups', 'oed_skills_groups(id_course)'),
                    'SkillsSteps'=>array(self::HAS_MANY, 'SkillsSteps', 'id_skill'),
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
            'condition' => 'Html текст',
            'n1' => 'N1',
            'n2' => 'N2',
            'n3' => 'N3',
            'skillsGroup' => 'Группа',
			'type' => 'Type',
                        'countExercises'=>'Число заданий',
                        'UnderSkills'=> 'Требуемое умение',
		);
	}
        
	public function searchKnowledge()
	{
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('type',1);
            $criteria->compare('id_course', $this->id_course);
            $data = new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    'pagination'=>false,
            ));
            
            $data2 = $data->getData();
            $newSkill = new Skills();
            $newSkill->type = 1;
            $data2[] = $newSkill;
            $data->setData($data2);
            return $data;
	}
        
	public function searchSkills()
	{
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('type',2);
            $criteria->compare('id_course', $this->id_course);

            $data =  new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    'pagination'=>false,
            ));
            $data2 = $data->getData();
            $newSkill = new Skills();
            $newSkill->type = 2;
            $data2[] = $newSkill;
            $data->setData($data2);
            return $data;
            
	}
        
        public function getIdsUsed(){
            $ids[] = $this->id;
            foreach($this->UnderSkills as $underSkill)
            {
                $ids[] = $underSkill->id;
            }
            return $ids;
        }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        function getCountExercises() {
            if(!isset(self::$cache[__FUNCTION__][$this->id]))
                self::$cache[__FUNCTION__][$this->id] = ExerciseAndSkills::model()->countByAttributes(array('id_skill'=>$this->id));//count($this->Exercises);
            return self::$cache[__FUNCTION__][$this->id];
        }
        
        function getCountExercisesGroups()
        {
            if(!isset(self::$cache[__FUNCTION__][$this->id]))
                self::$cache[__FUNCTION__][$this->id] = GroupExerciseAndSkills::model()->countByAttributes(array('id_skill'=>$this->id));//count($this->Exercises);
            return self::$cache[__FUNCTION__][$this->id];
        }
        
        function getCountTopSkills()
        {
            if(!isset(self::$cache[__FUNCTION__][$this->id]))
                self::$cache[__FUNCTION__][$this->id] = RelationSkills::model()->countByAttributes(array('id_skill'=>$this->id));//count($this->Exercises);
            return self::$cache[__FUNCTION__][$this->id];
        }
        
        function getCountCourses()
        {
            if(!isset(self::$cache[__FUNCTION__][$this->id]))
                self::$cache[__FUNCTION__][$this->id] = CourseAndSkills::model()->countByAttributes(array('id_skill'=>$this->id));
            return self::$cache[__FUNCTION__][$this->id];
        }
        
        function getCanDelete() {
            if($this->CountExercises || $this->CountExercisesGroups || $this->CountTopSkills || $this->CountCourses)
                return 0;
            return 1;
        }
        
        public function HtmlForCourse($id_course, $n, $title = true) {
            return "
            <div data-id='$this->id' class='skill-course' style='background: #". Courses::$bgColors[$n%count(Courses::$bgColors)] ."; width:164px;'>"
                ."<span class=name title='$this->name'>".($title?$this->name:"")."</span>". CHtml::link("<i class='glyphicon glyphicon-remove'></i>", array('/admin/courseandskills/delete', 'id_skill'=>$this->id, 'id_course'=>$id_course), array('title'=>$this->name, 'class'=>'skill-remove-icon') )."</div>";
        }
        
        public function existsInCourse($course_id)
        {
            return CourseAndSkills::model()->countByAttributes(array('id_course'=>$course_id, 'id_skill'=>$this->id));
        }
        
        public function hasBlocks($course_id)
        {
//            $criteria = new CDbCriteria();
//            $criteria->together = true;
//            $criteria->with = array('GroupOfExercises', 'GroupOfExercises.GroupAndSkills');
//            $criteria->compare('id_course', $course_id);
//            $criteria->compare('GroupAndSkills.id_skill', $this->id);
            return 1;//CoursesAndGroupExercise::model()->count($criteria);
        }
        
        public function afterDelete() {
            RelationSkills::model()->deleteAll('id_main_skill=:id or id_skill=:id', array('id'=>$this->id));
            UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_skill'=>$this->id));
            parent::afterDelete();
        }
        
        public static function skillsForAjax($id_course=null, $ids=null, $firstIds=null)
        {
            $result = array();
            $sql = '';
            $params = array();
            $ids = self::makeNumberMass($ids);
            $firstIds = array_diff(self::makeNumberMass($firstIds), $ids); // вывести сначала умения с определенными id
            $condition = array(0=>1);
            if(isset($_POST['term']))
            {
                $condition[] = '`name` LIKE :name';
                $params['name'] = '%' . $_POST['term'] . '%';
            }
            
            if($firstIds)
            {
                $sql .= "SELECT * FROM `oed_skills` WHERE `id` IN (".implode(',', $firstIds).") UNION ";
                $ids = array_merge($ids, $firstIds);
            }
            
            if($id_course && Courses::existCourseById($id_course))
            {
                if($ids)
                {
                    $condition[] = "`id` NOT IN(".implode(',', $ids).")";
                }
                
                $condition[] = '`id_course`=:id_course';
                $params['id_course'] = $id_course;
            }
            
            $sql .= "SELECT * FROM `oed_skills` WHERE ".implode(' AND ', $condition);

            $skills = Skills::model()->findAllBySql($sql, $params);
            
            foreach ($skills  as $skill)
            {
                $result['html'] .= "<li data-id='$skill->id'><a href='#'>$skill->name</a></li>";
            }
            if(!$result['html'])
            {
                $result['html'] = '<li><a href="#">Результатов нет</a></li>';
            }
            
            return CJSON::encode($result);
        }
        
        public static function makeNumberMass($mass)
        {
            $newMass = array();
            if($mass && is_array($mass))
            {
                foreach($mass as $item)
                {
                    $newMass[] = (int) $item;
                }
            }
            return $newMass;
        }
        
}
