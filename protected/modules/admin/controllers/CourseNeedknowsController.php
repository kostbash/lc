<?php

class CourseNeedknowsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public function filters()
	{
            return array(
                    'accessControl', 
                    'postOnly + delete',
            );
	}
        
	public function accessRules()
	{
            return array(
                    array('allow',
                            'actions'=>array('create', 'update', 'delete'),
                            'roles'=>array('editor'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}

        public function actionCreate($id_course)
        {
            $result = array('success'=>0);
            if(Courses::existCourseById($id_course))
            {
                if($_POST['CourseNeedknows'])
                {
                    $model = new CourseNeedknows;
                    $model->name = $_POST['CourseNeedknows']['name'];
                    $model->id_course = $id_course;
                    if($model->save())
                    {
                        $result['success'] = 1;
                    }
                    else
                    {
                        $result['errors'] = print_r($model->errors, true);
                    }
                }
            }
            else
            {
                $result['errors'] = 'У вас нет прав редактировать этот курс';
            }
            echo CJSON::encode($result);
        }
        
        public function actionUpdate()
        {
            $result = array('success'=>0);
            if($_POST['CourseNeedknows'])
            {
                foreach($_POST['CourseNeedknows'] as $id => $attributes)
                {
                    $model = $this->loadModel($id);
                    $model->attributes = $attributes;
                    if($model->save())
                    {
                        $result['success'] = 1;
                    }
                    else
                    {
                        $result['errors'] = print_r($model->errors, true);
                    }
                }
            }
            echo CJSON::encode($result);
        }

	public function actionDelete($id)
	{
            $this->loadModel($id)->delete();
	}

	public function loadModel($id)
	{
            $model = CourseNeedknows::model()->findByPk($id);
            if($model===null or !Courses::existCourseById($model->id_course))
                throw new CHttpException(404,'The requested page does not exist.');
            return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CourseNeedknows $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='course-needknows-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
