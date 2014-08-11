<?php

class LessonsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','delete','create','update', 'skillsbyajax','skillsbyidsajax', 'savechange', 'createfromcourse', 'changename', 'createincourse', 'addexercisegroup', 'changepositions'),
				'roles'=>array('editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Lessons;

		if(isset($_POST['Lessons']))
		{
			$model->attributes=$_POST['Lessons'];
			if($model->save())
				$this->redirect(array('/admin/lessons/update', 'id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
        
	public function actionCreateFromCourse($id_group)
	{
		$model=new Lessons;
                
                $lessonGroup = GroupOfLessons::model()->findByPk($id_group);

                if(!$lessonGroup)
                    die('Такой группы уроков не сущесвует');
                
		if(isset($_POST['Lessons']))
		{
			$model->attributes=$_POST['Lessons'];
                        if($model->save())
                        {
                            $lessonAndGroup = new GroupAndLessons();
                            $lessonAndGroup->id_group = $id_group;
                            $lessonAndGroup->id_lesson = $model->id;
                            $lessonAndGroup->order = GroupAndLessons::maxValueOrder($id_group);
                            if($lessonAndGroup->save())
                                echo 1;
                        }	
		}
	}
        
	public function actionSaveChange($id_course, $id_group, $id_lesson)
	{
                if(trim($_POST['nameLesson'])=='')
                    die('Введите название урока');
                
		$model= Lessons::model()->findByPk($id_lesson);
                if(!$model)
                    die('Не существует такого урока !');
                
                $groupAndLesson = GroupAndLessons::model()->findByAttributes(array('id_group'=>$id_group, 'id_lesson'=>$id_lesson));
                if(!$groupAndLesson)
                    die('У группы нет данного урока');
                // если урок можно сохранить из курса
                if($model->canSaveFromCourse($id_course)) {
                    $model->name = $_POST['nameLesson'];
                    $usersLesson = UserAndLessons::model()->findAllByAttributes(array('id_lesson'=>$id_lesson));
                    if($_POST['idsSkills'])
                    {
                        $criteria = new CDbCriteria;
                        $criteria->condition = '`id_lesson` = :id_lesson';
                        $criteria->params['id_lesson'] = $id_lesson;
                        $criteria->addNotInCondition('id_skill', $_POST['idsSkills']);
                        LessonAndSkills::model()->deleteAll($criteria);
                        
                        $userCriteria = new CDbCriteria;
                        $userCriteria->condition = '`id_user_and_lesson` = :id_user_and_lesson';
                        $userCriteria->addNotInCondition('id_skill', $_POST['idsSkills']);
                        if($usersLesson)
                            foreach($usersLesson as $userLesson) {
                                $userCriteria->params['id_user_and_lesson'] = $userLesson->id;
                                UserExerciseGroupSkills::model()->deleteAll($userCriteria);
                            }
                        foreach($_POST['idsSkills'] as $idSkill)
                        {
                            if(!LessonAndSkills::model()->findByAttributes(array('id_lesson'=>$id_lesson, 'id_skill'=>$idSkill)))
                            {
                                $lessonAndSkills = new LessonAndSkills();
                                $lessonAndSkills->id_lesson = $id_lesson;
                                $lessonAndSkills->id_skill = $idSkill;
                                $lessonAndSkills->save();
                            }
                        }
                    } else {
                        LessonAndSkills::model()->deleteAllByAttributes(array('id_lesson'=>$id_lesson));
                        if($usersLesson)
                            foreach($usersLesson as $userLesson)
                                UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                    }
                    if($model->save())
                        echo 1;
                    
                } else {
                    $newModel = new Lessons();
                    $newModel->attributes = $model->attributes;
                    $newModel->name = $_POST['nameLesson'];
                    $newModel->course_creator_id = $id_course;
                    if($newModel->save())
                    {
                        $groupAndLesson->id_lesson = $newModel->id;
                        $groupAndLesson->save();
                        
                        $usersLesson = UserAndLessons::model()->findAllByAttributes(array('id_group'=>$id_group, 'id_lesson'=>$id_lesson));
                        if($usersLesson)
                        {
                            foreach($usersLesson as $userLesson) {
                                $usersExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                                if($usersExerciseGroups)
                                {
                                    foreach($usersExerciseGroups as $usersExerciseGroup)
                                    {
                                        UserAndExercises::model()->deleteAllByAttributes(array('id_relation'=>$usersExerciseGroup->id));
                                        $usersExerciseGroup->delete();
                                    }
                                }
                                UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                                $userLesson->delete();
                            }
                        }

                        if($_POST['idsSkills'])
                        {
                            $lessonAndSkills = new LessonAndSkills();
                            foreach($_POST['idsSkills'] as $idSkill)
                            {
                                $currentLesson = LessonAndSkills::model()->findByAttributes(array('id_lesson'=>$id_lesson, 'id_skill'=>$idSkill));
                                if($currentLesson)
                                {
                                    $currentLesson->id_lesson = $newModel->id;
                                    $currentLesson->id = false;
                                    $currentLesson->isNewRecord = true;
                                    $currentLesson->save();
                                } else {
                                    $lessonAndSkills->id_lesson = $newModel->id;
                                    $lessonAndSkills->id_skill = $idSkill;
                                    $lessonAndSkills->save();
                                    $lessonAndSkills->id = false;
                                    $lessonAndSkills->isNewRecord = true;
                                }
                            }
                        }
                        echo '1';
                    }
                }
                    
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                $skills = new LessonAndSkills('search');
                $skills->id_lesson = $model->id;
                
		if(isset($_POST['Lessons']))
		{
                    $model->attributes=$_POST['Lessons'];
                    foreach($model->ExercisesGroups as $groupMassKey => $exerciseGroup) {
                        $exerciseGroup->attributes = $_POST['GroupOfExercises'][$groupMassKey];
                        $exerciseGroup->save();
                    }
                    if($model->save())
                            $this->redirect(array('/admin/lessons/update', 'id'=>$id));
		}

		$this->render('update',array(
			'model'=>$model,
                        'skills'=>$skills,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDeleteWithGroup($id)
	{
            $model = $this->loadModel($id);
            if($model->delete())
                echo 1;
	}
        
	public function actionDelete()
	{
            $id_lesson = (int) $_POST['id_lesson'];
            $id_course = (int) $_POST['id_course'];
            $model = $this->loadModel($id_lesson);
            $course = Courses::model()->findByPk($id_course);
            if(!$course)
                die('Такого курса не существует');
            
            $n = 1;
            $blocksCourse = $course->CoursesAndGroupExercise;
            
            foreach($model->ExercisesGroups as $group)
            {
                $usersExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$group->id));
                
                foreach($usersExerciseGroups as $usersExerciseGroup)
                {
                    $usersExerciseGroup->delete();
                }
                
                $courseAndGroupExercise = new CoursesAndGroupExercise;
                $courseAndGroupExercise->id_course = $id_course;
                $courseAndGroupExercise->id_group = $group->id;
                $courseAndGroupExercise->order = $n++;
                $courseAndGroupExercise->save(false);
            }
            
            foreach($blocksCourse as $block) {
                $block->order = $n++;
                $block->save(false);
            }
            
            CoursesAndLessons::model()->deleteAllByAttributes(array('id_lesson'=>$this->id));
            GroupAndLessons::model()->deleteAllByAttributes(array('id_lesson'=>$this->id));
            LessonAndExerciseGroup::model()->deleteAllByAttributes(array('id_lesson'=>$this->id));
            UserAndLessons::model()->deleteAllByAttributes(array('id_lesson'=>$this->id));
            
            // удаляем так, чтобы не сработал afterdelete, который удалит все группы
            if(Lessons::model()->deleteByPk($id_lesson))
                echo 1;
	}

	public function actionIndex($id_course=null, $id_group=null)
	{
		$model=new Lessons('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Lessons']))
			$model->attributes=$_GET['Lessons'];
                
                if($id_course && $id_group && $_POST['checked'])
                {
                    $course = Courses::model()->findByPk($id_course);
                    $groupLesson = GroupOfLessons::model()->findBypk($id_group);
                    if($course && $groupLesson)
                    {
                        $groupAndLessons = new GroupAndLessons();
                        foreach($_POST['checked'] as $id_lesson)
                        {
                            if(!GroupAndLessons::model()->findByAttributes(array('id_group'=>$id_group, 'id_lesson'=>$id_lesson)))
                            {
                                $groupAndLessons->id_group = $id_group;
                                $groupAndLessons->id_lesson = $id_lesson;
                                $groupAndLessons->order = GroupAndLessons::maxValueOrder($id_group);
                                $groupAndLessons->save(false);
                                $groupAndLessons->id = false;
                                $groupAndLessons->isNewRecord = true;
                            }
                        }
                        $this->redirect(array('/admin/courses/update', 'id'=>$id_course));
                    }
                }
                    

		$this->render('index',array(
			'model'=>$model,
                        'idLessonGroup' => $id_group,
		));
	}

	public function actionSkillsByAjax($id_lesson)
	{
            $lesson = $this->loadModel($id_lesson);
            
            $criteria = new CDbCriteria;

            if (isset($_POST['term']))// если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            $criteria->addNotInCondition('id', $lesson->idsUsedSkills);
            $criteria->limit = 10;
            $skills = Skills::model()->findAll($criteria);
            $res = '';
            foreach ($skills  as $skill)
            {
                $res .= "<li data-id='$skill->id'><a href='#'>$skill->name</a></li>";
            }
            if($res=='')
                $res = '<li><a href="#">Результатов нет</a></li>';
            echo $res;
	}
        
	public function actionSkillsByIdsAjax()
	{
            $criteria = new CDbCriteria;
            
            if (isset($_POST['term']))// если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            if($_POST['ids_skills'])
                $criteria->addNotInCondition('id', $_POST['ids_skills']);
            $criteria->limit = 10;
            $skills = Skills::model()->findAll($criteria);
            $res = '';
            foreach ($skills  as $skill)
            {
                $res .= "<li data-id='$skill->id'><a href='#'>$skill->name</a></li>";
            }
            if($res=='')
                $res = '<li><a href="#">Результатов нет</a></li>';
            echo $res;
	}
        
	public function loadModel($id)
	{
		$model=Lessons::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Lessons $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lessons-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionChangeName() {
            if($_POST['Lessons'])
            {
                foreach($_POST['Lessons'] as $id_lesson => $attr)
                {
                    $model=$this->loadModel($id_lesson);
                    $model->name = trim($attr['name']);
                    $model->save(false);
                }
            }
        }
        
        public function actionCreateInCourse($id_course)
	{
            $name = trim($_POST['name']);
            if($name && Courses::model()->findByPk($id_course))
            {
                $model=new Lessons;
                $model->name = $name;
                if($model->save())
                {
                    $courseAndLesson = new CoursesAndLessons;
                    $courseAndLesson->id_course = $id_course;
                    $courseAndLesson->id_lesson = $model->id;
                    $courseAndLesson->order = CoursesAndLessons::maxValueOrder($id_course);
                    if($courseAndLesson->save())
                    {
                        $res['success'] = 1;
                        $res['html'] = $model->htmlForCourse;
                        echo CJSON::encode($res, true);
                    }
                }
            }
	}
        
        public function actionAddExerciseGroup($id_course) {
            $id_lesson = (int) $_POST['id_lesson'];
            $id_group = (int) $_POST['id_group'];
            
            if($id_course && $id_group && $id_lesson)
            {
                CoursesAndGroupExercise::model()->deleteAllByAttributes(array('id_course'=>$id_course, 'id_group'=>$id_group));
                $lessonExerciseGroup = new LessonAndExerciseGroup;
                $lessonExerciseGroup->id_group_exercises = $id_group;
                $lessonExerciseGroup->id_lesson = $id_lesson;
                $lessonExerciseGroup->order = LessonAndExerciseGroup::maxValueOrder($id_lesson);
                $lessonExerciseGroup->save();
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
        }
        
        public function actionChangePositions() {
            $id_lesson = (int) $_POST['id_lesson'];
            $id_course = (int) $_POST['id_course'];
            if($id_lesson)
            {
                if($_POST['positions'])
                {
                    foreach($_POST['positions'] as $key => $id_group)
                    {
                        $criteria = new CDbCriteria;
                        $criteria->addNotInCondition('id_group_exercises', $_POST['positions']);
                        $criteria->compare('id_lesson', $id_lesson);
                        LessonAndExerciseGroup::model()->deleteAll($criteria);
                        $lessonAndExerciseGroup = LessonAndExerciseGroup::model()->findByAttributes(array('id_lesson'=>$id_lesson, 'id_group_exercises'=>$id_group));
                        if($lessonAndExerciseGroup)
                        {
                            $lessonAndExerciseGroup->order = $key+1;
                            $lessonAndExerciseGroup->save();
                        } else {
                            $lessonAndExerciseGroup = new LessonAndExerciseGroup;
                            $lessonAndExerciseGroup->id_lesson = $id_lesson;
                            $lessonAndExerciseGroup->id_group_exercises = $id_group;
                            $lessonAndExerciseGroup->order = $key+1;
                            $lessonAndExerciseGroup->save();
                        }
                    }
                    
                    $userExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$id_group));
                    foreach($userExerciseGroups as $userExerciseGroup)
                    {
                        $userExerciseGroup->delete();
                    }
                } else {
                    LessonAndExerciseGroup::model()->deleteAllByAttributes(array('id_lesson'=>$id_lesson));
                }
                $res['success'] = 1;
            } elseif($id_course) {
                if($_POST['positions'])
                {
                    foreach($_POST['positions'] as $key => $id_group)
                    {
                        $criteria = new CDbCriteria;
                        $criteria->addNotInCondition('id_group', $_POST['positions']);
                        $criteria->compare('id_course', $id_course);
                        CoursesAndGroupExercise::model()->deleteAll($criteria);
                        $coursesAndGroupExercise = CoursesAndGroupExercise::model()->findByAttributes(array('id_course'=>$id_course, 'id_group'=>$id_group));
                        if($coursesAndGroupExercise)
                        {
                            $coursesAndGroupExercise->order = $key+1;
                            $coursesAndGroupExercise->save();
                        } else {
                            $coursesAndGroupExercise = new CoursesAndGroupExercise;
                            $coursesAndGroupExercise->id_course = $id_course;
                            $coursesAndGroupExercise->id_group = $id_group;
                            $coursesAndGroupExercise->order = $key+1;
                            $coursesAndGroupExercise->save();
                        }
                    }
                    $userExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$id_group));
                    foreach($userExerciseGroups as $userExerciseGroup)
                    {
                        $userExerciseGroup->delete();
                    }
                } else {
                    CoursesAndGroupExercise::model()->deleteAllByAttributes(array('id_course'=>$id_course));
                }
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
        }
}
