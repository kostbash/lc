<?php

class ExercisesController extends Controller
{
	public $layout='//layouts/column2';

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('right'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionRight($id_relation)
	{
            if($_POST['Exercises']) {
                $id = (int) key($_POST['Exercises']);
                $answer = is_array($_POST['Exercises'][$id]['answers']) ? implode(',', $_POST['Exercises'][$id]['answers']) : trim($_POST['Exercises'][$id]['answers']);
                $userExercise = UserAndExercises::model()->findByAttributes(array('id_relation'=>$id_relation, 'id_exercise'=>$id));
                if(!$userExercise)
                {
                    $userExercise = new UserAndExercises;
                    $userExercise->id_relation = $id_relation;
                    $userExercise->id_exercise = $id;
                }
                $userExercise->last_answer = 
                $userExercise->save(false);
                $exercise = Exercises::model()->findByPk($id);
                echo Exercises::rightAnswer($id, $_POST['Exercises'][$id]['answers']);
            }
	}

	public function loadModel($id)
	{
		$model=Exercises::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exercises-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
