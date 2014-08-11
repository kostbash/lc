<?php

class ExercisesLogsController extends Controller
{
	public $layout='/layouts/column2';

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
                            'actions'=>array('view'),
                            'roles'=>array('parent', 'teacher'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
	public function actionView($id)
	{
            $model = $this->loadModelWitCheckAccess($id); 
            
            $this->render('view',array(
                    'model'=>$model,
                    'exercise'=>$model->Exercise,
            ));
	}
        
	public function loadModel($id)
	{
            $model=  UserExercisesLogs::model()->findByPk($id);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
	}
        
	public function loadModelWitCheckAccess($id)
	{
            $exist = UserExercisesLogsAndTeacher::model()->exists('id_log=:id_log AND id_teacher=:id_teacher', array('id_log'=>$id, 'id_teacher'=>Yii::app()->user->id));
            if(!$exist)
                    throw new CHttpException(404,'The requested page does not exist.');
            return UserExercisesLogs::model()->findByPk($id);
	}

	protected function performAjaxValidation($model)
	{
            if(isset($_POST['ajax']) && $_POST['ajax']==='children-of-parent-form')
            {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }
	}
        
}
