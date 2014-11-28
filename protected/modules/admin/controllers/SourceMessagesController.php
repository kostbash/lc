<?php

class SourceMessagesController extends Controller
{
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
                            'actions'=>array('index', 'update'),
                            'roles'=>array('admin'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
        }

	public function actionUpdate($id)
	{
                $result = array('success'=>0);

		if(isset($_POST['SourceMessages']))
		{
                    $model=$this->loadModel($id);
                    $model->attributes=$_POST['SourceMessages'][$id];
                    if($model->save())
                            $result['success'] = 1;
                    else
                        $result['errors'] = $model->errors['message'][0];
		}
                
                echo CJSON::encode($result);
	}

	public function actionIndex()
	{
		$model=new SourceMessages('search');
		$model->unsetAttributes();
		if(isset($_GET['SourceMessages']))
			$model->attributes=$_GET['SourceMessages'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=SourceMessages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='source-messages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
