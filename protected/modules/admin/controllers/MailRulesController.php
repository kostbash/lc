<?php

class MailRulesController extends Controller
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
                            'actions'=>array('create', 'update', 'delete', 'index'),
                            'roles'=>array('admin'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
	public function actionCreate()
	{
		$model=new MailRules;
                $model->attributes = $model::$defaultValues;

		if(isset($_POST['MailRules']))
		{
                    $model->unsetAttributes(array('passed_reg_days', 'unactivity_days', 'number_of_passed_lessons', 'passed_course', 'number_of_passed_courses', 'unpassed_check_test'));
                    $model->attributes=$_POST['MailRules'];
                    $model->roles = serialize($_POST['MailRules']['roles']);
                    if($model->save())
                        $this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                $cloneModel = clone $model;
                
		if(isset($_POST['MailRules']))
		{
                    $model->unsetAttributes(array('passed_reg_days', 'unactivity_days', 'number_of_passed_lessons', 'passed_course', 'number_of_passed_courses', 'unpassed_check_test'));
                    $model->attributes=$_POST['MailRules'];
                    $model->roles = serialize($_POST['MailRules']['roles']);
                    if($model->save())
                    {
                        $this->redirect(array('index'));
                    }
		}

		$this->render('update',array(
			'model'=>$model,
                        'cloneModel'=>$cloneModel,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionIndex()
	{
		$model=new MailRules('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MailRules']))
			$model->attributes=$_GET['MailRules'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return MailRules the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=MailRules::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param MailRules $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mail-rules-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
