<?php

class GroupandexercisesController extends Controller
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

	public function accessRules()
	{
            return array(
                    array('allow', // allow admin user to perform 'admin' and 'delete' actions
                            'actions'=>array('delete','create', 'changeordergroup', 'changeorderexercise'),
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
		$model=new GroupAndExercises;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GroupAndExercises']))
		{
			$model->attributes=$_POST['GroupAndExercises'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

        public function actionChangeOrderExercise($id_group)
        {
            if($_POST['id_exercise'] && $_POST['id_sibling_exercise'])
            {
                $current = GroupAndExercises::model()->findByAttributes(array('id_group'=>$id_group, 'id_exercise'=>$_POST['id_exercise']));
                $sibling = GroupAndExercises::model()->findByAttributes(array('id_group'=>$id_group, 'id_exercise'=>$_POST['id_sibling_exercise']));
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
        
        public function actionChangeOrderGroup($id_lesson)
        {
            if($_POST['id_group'] && $_POST['id_sibling_group'])
            {
                $current = LessonAndExerciseGroup::model()->findByAttributes(array('id_lesson'=>$id_lesson, 'id_group_exercises'=>$_POST['id_group']));
                $sibling = LessonAndExerciseGroup::model()->findByAttributes(array('id_lesson'=>$id_lesson, 'id_group_exercises'=>$_POST['id_sibling_group']));
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
        
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GroupAndExercises']))
		{
			$model->attributes=$_POST['GroupAndExercises'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id_group, $id_exercise)
	{
		$model = GroupAndExercises::model()->findByAttributes(array('id_group'=>$id_group, 'id_exercise'=>$id_exercise));
                if($model) {
                    $model->delete();
                    $userExercisesGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$id_group));
                    foreach($userExercisesGroups as $userExerciseGroup)
                    {
                        $userAndExercise = UserAndExercises::model()->findByAttributes(array('id_relation'=>$userExerciseGroup->id, 'id_exercise'=>$id_exercise));
                        $userAndExercise->delete();
                    }
                }
	}
        
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('GroupAndExercises');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GroupAndExercises('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GroupAndExercises']))
			$model->attributes=$_GET['GroupAndExercises'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GroupAndExercises the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GroupAndExercises::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GroupAndExercises $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-and-exercises-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
