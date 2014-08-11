<?php

class GeneratorsTemplatesVariablesController extends Controller
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
                    array('allow',
                            'actions'=>array('gethtmlvars'),
                            'roles'=>array('editor'),
                    ),
                    array('deny',
                            'users'=>array('*'),
                    ),
            );
	}
        
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

        public function actionGetHtmlVars() {
            $names = array_unique(((array) $_POST['names'])); // преобразовываем names в массив(если не массив). И убераем повторяющиеся имена
            $lastNum = (int) $_POST['lastNum'];
            $result = array();
            if($names)
            {
                $generatorVariable = new GeneratorsTemplatesVariables;
                foreach ($names as $name)
                {
                    $generatorVariable->name = $name;
                    $result['html'] .= $generatorVariable->getHtml(++$lastNum);
                }
                $result['success'] = 1;
            } else {
                $result['success'] = 0;
            }
            echo CJSON::encode($result);
        }
        
	public function actionCreate()
	{
		$model=new GeneratorsTemplatesVariables;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeneratorsTemplatesVariables']))
		{
			$model->attributes=$_POST['GeneratorsTemplatesVariables'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeneratorsTemplatesVariables']))
		{
			$model->attributes=$_POST['GeneratorsTemplatesVariables'];
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
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('GeneratorsTemplatesVariables');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GeneratorsTemplatesVariables('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GeneratorsTemplatesVariables']))
			$model->attributes=$_GET['GeneratorsTemplatesVariables'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GeneratorsTemplatesVariables the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GeneratorsTemplatesVariables::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GeneratorsTemplatesVariables $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='generators-templates-variables-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
