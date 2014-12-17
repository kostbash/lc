<?php

class CoursesController extends Controller
{
	public $layout='/layouts/column2';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('create','update','delete','index', 'changeorderlessongroup', 'changepositions', 'coursesbyajax', 'params'),
				'roles'=>array('editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
	public function actionCreate()
	{
		$model=new Courses;

		if(isset($_POST['Courses']))
		{
			$model->attributes=$_POST['Courses'];
			$model->id_editor=Yii::app()->user->id;
                        $model->change_date = date('Y-m-d H:i:s');
			if($model->save())
                        {
                            if($_POST['Courses']['Subjects'] && is_array($_POST['Courses']['Subjects']))
                            {
                                foreach($_POST['Courses']['Subjects'] as $id_subject)
                                {
                                    if(!Courses::hasSubject($model->id, $id_subject))
                                    {
                                        $coursesAndSubjects = new CoursesAndSubjects;
                                        $coursesAndSubjects->id_course = $model->id;
                                        $coursesAndSubjects->id_subject = $id_subject;
                                        $coursesAndSubjects->save();
                                    }
                                }
                            }

                            if($_POST['Courses']['Classes'] && is_array($_POST['Courses']['Classes']))
                            {
                                foreach($_POST['Courses']['Classes'] as $id_class)
                                {
                                    if(!Courses::hasClass($model->id, $id_class))
                                    {
                                        $coursesAndClasses = new CoursesAndClasses;
                                        $coursesAndClasses->id_course = $model->id;
                                        $coursesAndClasses->id_class = $id_class;
                                        $coursesAndClasses->save();
                                    }
                                }
                            }
                            
                            if($_POST['Courses']['Needknows'] && is_array($_POST['Courses']['Needknows']))
                            {
                                foreach($_POST['Courses']['Needknows'] as $needknow_name)
                                {
                                    $courseNeedknow = new CourseNeedknows;
                                    $courseNeedknow->name = $needknow_name;
                                    $courseNeedknow->id_course = $model->id;
                                    $courseNeedknow->save();
                                }
                            }
                            
                            if($_POST['Courses']['Yougets'] && is_array($_POST['Courses']['Yougets']))
                            {
                                foreach($_POST['Courses']['Yougets'] as $youget_name)
                                {
                                    $courseYouget = new CourseYougets;
                                    $courseYouget->name = $youget_name;
                                    $courseYouget->id_course = $model->id;
                                    $courseYouget->save();
                                }
                            }
                            
                            if($_POST['Students'] && is_array($_POST['Students']))
                            {
                                foreach($_POST['Students'] as $id_student)
                                {
                                    if(CourseUserList::canAdd($model->id, $id_student, false))
                                    {
                                        $userList = new CourseUserList;
                                        $userList->id_course = $model->id;
                                        $userList->id_student = $id_student;
                                        $userList->save();
                                    }
                                }
                            }
                            
                            $this->redirect(array('update','id'=>$model->id));
                        }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                
                $this->menu[] = array('label'=>'Задания курса', 'url'=>array('/admin/exercises/index', 'id_course'=>$id));
                
		if(isset($_POST['Courses']))
		{
                    $lessonValid = true;
                    foreach($model->LessonsGroups as $key => $lessonGroup) {
                        $lessonGroup->attributes = $_POST['GroupOfLessons'][$key];
                        $lessonsGroups[] = $lessonGroup;
                        if(!$lessonGroup->validate())
                            $lessonValid = false;
                    }
			$model->attributes=$_POST['Courses'];
			if($model->validate() && $lessonValid)
                        {
                            $model->change_date = date('Y-m-d H:i:s');
                            $model->save(false);
                            foreach($lessonsGroups as $lessonGroup)
                                $lessonGroup->save(false);
                            $this->redirect(array('/admin/courses/update', 'id'=>$model->id));
                        }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
        
	public function actionParams($id_course)
	{
		$model=$this->loadModel($id_course);
                
                $this->menu[] = array('label'=>'Задания курса', 'url'=>array('/admin/exercises/index', 'id_course'=>$id));
                
		if(isset($_POST['Courses']))
		{
                    $model->attributes=$_POST['Courses'];
                    $model->change_date = date('Y-m-d H:i:s');
                    if($model->save())
                    {
                        if($_POST['Courses']['Subjects'] && is_array($_POST['Courses']['Subjects']))
                        {
                            foreach($_POST['Courses']['Subjects'] as $id_subject)
                            {
                                if(!Courses::hasSubject($id_course, $id_subject))
                                {
                                    $coursesAndSubjects = new CoursesAndSubjects;
                                    $coursesAndSubjects->id_course = $id_course;
                                    $coursesAndSubjects->id_subject = $id_subject;
                                    $coursesAndSubjects->save();
                                }
                            }
                            $criteria = new CDbCriteria;
                            $criteria->compare('id_course', $id_course);
                            $criteria->addNotInCondition('id_subject', $_POST['Courses']['Subjects']);
                            CoursesAndSubjects::model()->deleteAll($criteria);
                        }
                        else
                        {
                            CoursesAndSubjects::model()->deleteAllByAttributes(array('id_course'=>$id_course));
                        }
                        
                        if($_POST['Courses']['Classes'] && is_array($_POST['Courses']['Classes']))
                        {
                            foreach($_POST['Courses']['Classes'] as $id_class)
                            {
                                if(!Courses::hasClass($id_course, $id_class))
                                {
                                    $coursesAndClasses = new CoursesAndClasses;
                                    $coursesAndClasses->id_course = $id_course;
                                    $coursesAndClasses->id_class = $id_class;
                                    $coursesAndClasses->save();
                                }
                            }
                            $criteria = new CDbCriteria;
                            $criteria->compare('id_course', $id_course);
                            $criteria->addNotInCondition('id_class', $_POST['Courses']['Classes']);
                            CoursesAndClasses::model()->deleteAll($criteria);
                        }
                        else
                        {
                            CoursesAndClasses::model()->deleteAllByAttributes(array('id_course'=>$id_course));
                        }
                        
                        if($_POST['Students'] && is_array($_POST['Students']))
                        {
                            foreach($_POST['Students'] as $id_student)
                            {
                                if(CourseUserList::canAdd($id_course, $id_student))
                                {
                                    $userList = new CourseUserList;
                                    $userList->id_course = $id_course;
                                    $userList->id_student = $id_student;
                                    $userList->save();
                                }
                            }
                            
                            $criteria = new CDbCriteria;
                            $criteria->compare('id_course', $id_course);
                            $criteria->addNotInCondition('id_student', $_POST['Students']);
                            CourseUserList::model()->deleteAll($criteria);
                        }
                        else
                        {
                            CourseUserList::model()->deleteAllByAttributes(array('id_course'=>$id_course));
                        }
                        
                        $this->redirect(array('/admin/courses/update', 'id'=>$model->id));
                    }
		}

		$this->render('params',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
                $model->delete();
	}

	public function actionIndex()
	{
		$model=new Courses('search');

		$model->unsetAttributes();
		if(isset($_GET['Courses']))
			$model->attributes=$_GET['Courses'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

        // изменение порядка групп
        public function actionChangeOrderLessonGroup($id_course) {
            if($_POST['id_group'] && $_POST['id_sibling_group'])
            {
                $model = $this->loadModel($id_course);
                $current = CourseAndLessonGroup::model()->findByAttributes(array('id_course'=>$id_course, 'id_group_lesson'=>$_POST['id_group']));
                $sibling = CourseAndLessonGroup::model()->findByAttributes(array('id_course'=>$id_course, 'id_group_lesson'=>$_POST['id_sibling_group']));
                if($current && $sibling)
                {
                        $var = $current->order;
                        $current->order = $sibling->order;
                        $sibling->order = $var;
                        $model->changeDate();
                        if($current->save() && $sibling->save())
                            echo 1;
                }
            }
        }
        
	public function loadModel($id)
	{
            $model=Courses::CourseById($id);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Courses $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='courses-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionChangePositions($id_course) {
            if($id_course && $_POST['positions'])
            {
                $model = $this->loadModel($id_course);
                foreach($_POST['positions'] as $key => $id_group)
                {
                    $courseAndLessonGroup = CourseAndLessonGroup::model()->findByAttributes(array('id_course'=>$id_course, 'id_group_lesson'=>$id_group));
                    if($courseAndLessonGroup)
                    {
                        $courseAndLessonGroup->order = $key+1;
                        $courseAndLessonGroup->save();
                    }
                }
                $model->changeDate();
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
        }
        
	public function actionCoursesByAjax()
	{
            $result = array('success'=>1);
            $criteria = new CDbCriteria;
            if(isset($_POST['term']))
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }
            
            $criteria->limit = 10;
            $result['html'] = '';
            $courses = Courses::model()->findAll($criteria);
            if($courses)
            {
                foreach ($courses  as $course)
                {
                    $result['html'] .= "<li data-id='$course->id'><a href='#'>$course->name</a></li>";
                }
            }
            else
            {
                $result['html'] = '<li><a href="#">Результатов нет</a></li>';
            }
            
            echo CJSON::encode($result);
	}
        
}
