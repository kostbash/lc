<?php

class SkillsgroupsController extends Controller
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
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','create','update', 'delete', 'changeByAjax'),
				'roles'=>array('editor'),
			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
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
        $model=new SkillsGroups;
        if(isset($_POST['Groups']))
        {
            $model->attributes=$_POST['Groups'];
            if($model->save())
            {
                if(isset($_REQUEST['Groups']['fromcourse']) && $_REQUEST['Groups']['fromcourse'] )
                    echo $model->id;
                else
                    echo true;
            } else echo 'ff';

        }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
        if(isset($_POST['SkillsGroups']))
        {
            foreach($_POST['SkillsGroups'] as $id => $attributes)
            {
                $model = $this->loadModel($id);
                $model->attributes=$attributes;
                if($model->save())
                    echo true;
                else {
                    $message = null;
                    if ($model->errors) {
                        foreach($model->errors as $error) {
                            $message .= $error[0] . "\n";
                        }
                        echo $message;
                    } else {
                        echo 'Произошла ошибка при сохранении';
                    }
                }
            }
        }
	}

    public function actionChangeByAjax() {
        if (isset($_POST['skillId'], $_POST['groupId'])) {
            $model = Skills::model()->findByPk($_POST['skillId']);
            $model->skillsGroup = $_POST['groupId'];
            if ($model->save()) {
                echo true;
            } else {
                echo false;
            }
        }
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($id_course)
	{
        $course = Courses::model()->findByPk($id_course);
        $model=new SkillsGroups('search');
        $model->unsetAttributes();
        if($course)
            $model->id_course = $course->id;
        else
            $model->id_course = 0;
        $id_course = $course ? $course->id : 0;


		$this->render('index',array(
			'model'=>$model,
            'course' => $course,
            'id_course' => $id_course,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SkillsGroups('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SkillsGroups']))
			$model->attributes=$_GET['SkillsGroups'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SkillsGroups the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SkillsGroups::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SkillsGroups $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='skills-groups-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
