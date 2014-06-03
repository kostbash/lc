<?php

class ExerciseAndSkillsController extends Controller
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
                            echo 1;
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ExerciseAndSkills']))
		{
			$model->attributes=$_POST['ExerciseAndSkills'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
	public function actionDelete()
	{
            if($_POST['id_exercise'] && $_POST['id_skill']) {
		$model = ExerciseAndSkills::model()->findByAttributes(array('id_exercise'=>$_POST['id_exercise'], 'id_skill'=>$_POST['id_skill']));
                if($model) {
                    $model->delete();
                    echo 1;
                }
            }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ExerciseAndSkills');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ExerciseAndSkills('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExerciseAndSkills']))
			$model->attributes=$_GET['ExerciseAndSkills'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ExerciseAndSkills the loaded model
	 * @throws CHttpException
	 */
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
