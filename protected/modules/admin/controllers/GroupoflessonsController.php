<?php

class GroupoflessonsController extends Controller
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
                    array('allow', // allow admin user to perform 'admin' and 'delete' actions
                            'actions'=>array('create','delete', 'changename', 'addlesson', 'changepositions'),
                            'users'=>Users::Admins(),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}

	public function actionCreate($id_course)
	{
            $name = trim($_POST['name']);
            if($name && Courses::model()->findByPk($id_course))
            {
                $model=new GroupOfLessons;
                $model->name = $name;
                if($model->save())
                {
                    $courseAndLessonGroup = new CourseAndLessonGroup();
                    $courseAndLessonGroup->id_course = $id_course;
                    $courseAndLessonGroup->id_group_lesson = $model->id;
                    $courseAndLessonGroup->order = CourseAndLessonGroup::maxValueOrder($id_course);
                    if($courseAndLessonGroup->save())
                    {
                        $res['success'] = 1;
                        $res['html'] = $model->htmlForCourse;
                        echo CJSON::encode($res, true);
                    }
                }
            }
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GroupOfLessons']))
		{
			$model->attributes=$_POST['GroupOfLessons'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

//	public function actionDelete($id_course, $id)
//	{
//            $model = $this->loadModel($id);
//            foreach($model->GroupAndLessons as $groupAndLesson) {
//                $usersLesson = UserAndLessons::model()->findAllByAttributes(array('id_group'=>$groupAndLesson->id_group, 'id_lesson'=>$groupAndLesson->id_lesson));
//                if($usersLesson)
//                {
//                    foreach($usersLesson as $userLesson) {
//                        $usersExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
//                        if($usersExerciseGroups)
//                        {
//                            foreach($usersExerciseGroups as $usersExerciseGroup)
//                            {
//                                UserAndExercises::model()->deleteAllByAttributes(array('id_relation'=>$usersExerciseGroup->id));
//                                $usersExerciseGroup->delete();
//                            }
//                        }
//                        UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
//                        $userLesson->delete();
//                    }
//                }
//                $groupAndLesson->delete();
//            }
//            $courseAndLessonGroup = CourseAndLessonGroup::model()->findByAttributes(array('id_course'=>$id_course, 'id_group_lesson'=>$id));
//            
//            if($model->delete() && $courseAndLessonGroup->delete())
//                echo 1;
//	}
        
	public function actionDelete()
	{
            $id = (int) $_POST['id_theme'];
            $id_course = (int) $_POST['id_course'];
            $model = $this->loadModel($id);
            $course = Courses::model()->findByPk($id_course);
            if(!$course)
                die('Такого курса не существует');
            $themes = $course->CoursesAndLessons;
            $n = 1;
            foreach($model->LessonsRaw as $lesson) {
                foreach($lesson->ExercisesGroups as $group)
                {
                    $usersExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$group->id));

                    if($group->type == 1) {
                        foreach($usersExerciseGroups as $usersExerciseGroup)
                        {
                            UserAndExercises::model()->deleteAllByAttributes(array('id_relation'=>$usersExerciseGroup->id));
                            $usersExerciseGroup->delete();
                        }

                    } elseif ($group->type == 2) {

                        foreach($usersExerciseGroups as $usersExerciseGroup)
                        {
                            UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_test_group'=>$usersExerciseGroup->id));
                            $usersExerciseGroup->delete();
                        }
                    }
                }
                $courseLesson = new CoursesAndLessons;
                $courseLesson->id_course = $id_course;
                $courseLesson->id_lesson = $lesson->id;
                $courseLesson->order = $n++;
                $courseLesson->save();
            }
            
            foreach($themes as $theme)
            {
                $theme->order = $n++;
                $theme->save(false);
            }
            
            $courseAndLessonGroup = CourseAndLessonGroup::model()->findByAttributes(array('id_group_lesson'=>$id, 'id_course'=>$id_course));
            GroupAndLessons::model()->deleteAllByAttributes(array('id_group'=>$id));
            
            if($model->delete() && $courseAndLessonGroup->delete())
                echo 1;
	}

	public function loadModel($id)
	{
		$model=GroupOfLessons::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GroupOfLessons $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-of-lessons-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionChangeName() {
            $id = (int) $_POST['id_group'];
            $model=$this->loadModel($id);
            if($_POST['name'])
            {
                $model->name = trim($_POST['name']);
                $model->save(false);
            }
        }
        
        public function actionAddLesson($id_course) {
            $id_group = (int) $_POST['id_group'];
            $id_lesson = (int) $_POST['id_lesson'];
            if($id_course && $id_group && $id_lesson)
            {
                $lesson = Lessons::model()->findByPk($id_lesson);
                CoursesAndLessons::model()->deleteAllByAttributes(array('id_course'=>$id_course, 'id_lesson'=>$id_lesson));
                $groupAndLesson = new GroupAndLessons;
                $groupAndLesson->id_group = $id_group;
                $groupAndLesson->id_lesson = $id_lesson;
                $groupAndLesson->order = GroupAndLessons::maxValueOrder($id_group);
                $groupAndLesson->save();
                $res['success'] = 1;
                $res['html'] = $lesson->htmlforCourseWithBlocks($id_group);
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
        }
        
        public function actionChangePositions() {
            $id_theme = (int) $_POST['id_theme'];
            $id_course = (int) $_POST['id_course'];
            if($id_theme)
            {
                if($_POST['positions'])
                {
                    foreach($_POST['positions'] as $key => $id_lesson)
                    {
                        $criteria = new CDbCriteria;
                        $criteria->addNotInCondition('id_lesson', $_POST['positions']);
                        $criteria->compare('id_group', $id_theme);
                        GroupAndLessons::model()->deleteAll($criteria);
                        $groupAndLessons = GroupAndLessons::model()->findByAttributes(array('id_group'=>$id_theme, 'id_lesson'=>$id_lesson));
                        if($groupAndLessons)
                        {
                            $groupAndLessons->order = $key+1;
                            $groupAndLessons->save();
                        } else {
                            $groupAndLessons = new GroupAndLessons;
                            $groupAndLessons->id_group = $id_theme;
                            $groupAndLessons->id_lesson = $id_lesson;
                            $groupAndLessons->order = $key+1;
                            $groupAndLessons->save();
                        }
                    }
                } else {
                    GroupAndLessons::model()->deleteAllByAttributes(array('id_group'=>$id_theme));
                }
                $res['success'] = 1;
            } elseif($id_course) {
                if($_POST['positions'])
                {
                    foreach($_POST['positions'] as $key => $id_lesson)
                    {
                        $criteria = new CDbCriteria;
                        $criteria->addNotInCondition('id_lesson', $_POST['positions']);
                        $criteria->compare('id_course', $id_course);
                        CoursesAndLessons::model()->deleteAll($criteria);
                        $coursesAndLessons = CoursesAndLessons::model()->findByAttributes(array('id_course'=>$id_course, 'id_lesson'=>$id_lesson));
                        if($coursesAndLessons)
                        {
                            $coursesAndLessons->order = $key+1;
                            $coursesAndLessons->save();
                        } else {
                            $coursesAndLessons = new CoursesAndLessons;
                            $coursesAndLessons->id_course = $id_course;
                            $coursesAndLessons->id_lesson = $id_lesson;
                            $coursesAndLessons->order = $key+1;
                            $coursesAndLessons->save();
                        }
                    }
                } else {
                    CoursesAndLessons::model()->deleteAllByAttributes(array('id_course'=>$id_course));
                }
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
        }
}
