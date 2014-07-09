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
				'actions'=>array('create','update','delete','index', 'changeorderlessongroup', 'changepositions'),
				'users'=>Users::Admins(),
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
		$model=new Courses;

		if(isset($_POST['Courses']))
		{
			$model->attributes=$_POST['Courses'];
			if($model->save())
				$this->redirect(array('update','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                
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

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
                $model->delete();
	}

	public function actionIndex()
	{
		$model=new Courses('search');
		$model->unsetAttributes();  // clear any default values
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
                $current = CourseAndLessonGroup::model()->findByAttributes(array('id_course'=>$id_course, 'id_group_lesson'=>$_POST['id_group']));
                $sibling = CourseAndLessonGroup::model()->findByAttributes(array('id_course'=>$id_course, 'id_group_lesson'=>$_POST['id_sibling_group']));
                if($current && $sibling)
                {
                        $var = $current->order;
                        $current->order = $sibling->order;
                        $sibling->order = $var;
                        if($current->save() && $sibling->save())
                            echo 1;
                }
            }
        }
        
	public function loadModel($id)
	{
		$model=Courses::model()->findByPk($id);
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
                foreach($_POST['positions'] as $key => $id_group)
                {
                    $courseAndLessonGroup = CourseAndLessonGroup::model()->findByAttributes(array('id_course'=>$id_course, 'id_group_lesson'=>$id_group));
                    if($courseAndLessonGroup)
                    {
                        $courseAndLessonGroup->order = $key+1;
                        $courseAndLessonGroup->save();
                    }
                }
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
        }
        
}
