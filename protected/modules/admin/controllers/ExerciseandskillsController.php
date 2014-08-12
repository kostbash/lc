<?php

class ExerciseandskillsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','delete','create','update'),
				'roles'=>array('editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
	public function actionCreate($id_exercise)
	{
                $exercise = Exercises::model()->findByPk($id_exercise);
                if(!$exercise)
                    die("Задания не существует");
                if($_POST['id_skill'])
                { 
                    $skill = Skills::model()->findByPk($_POST['id_skill']);
                    if(!$skill)
                        die("Такого умения не сущесвует");
                    if(ExerciseAndSkills::model()->findByAttributes(array('id_exercise'=>$exercise->id, 'id_skill'=>$skill->id)))
                            die("У задания уже есть такое умение");
                    $model= new ExerciseAndSkills();
                    $model->id_exercise = $exercise->id;
                    $model->id_skill = $skill->id;
                    if($model->save())
                    {
                        $exercise->change_date = date('Y-m-d H:i:s');
                        $exercise->save();
                        echo 1;
                    }
		}
	}

	public function actionDelete()
	{
            if($_POST['id_exercise'] && $_POST['id_skill']) {
		$model = ExerciseAndSkills::model()->findByAttributes(array('id_exercise'=>$_POST['id_exercise'], 'id_skill'=>$_POST['id_skill']));
                if($model)
                {
                    $model->delete();
                    if($exercise=$model->Exercise)
                    {
                        $exercise->change_date = date('Y-m-d H:i:s');
                        $exercise->save();
                        echo 1;
                    }
                }
            }
	}
        
	public function loadModel($id)
	{
		$model=ExerciseAndSkills::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ExerciseAndSkills $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exercise-and-skills-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
