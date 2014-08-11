<?php

class RelationskillsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column2';

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
				'actions'=>array('delete','create'),
				'roles'=>array('editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate()
	{
            if(isset($_POST['id_main']) && isset($_POST['id']) && $_POST['id_main'] !=$_POST['id'])
            {    
                $skillMain = Skills::model()->findByPk($_POST['id_main']);
                $skill = Skills::model()->findByPk($_POST['id']);
                
                if(!($skillMain && $skill))
                {
                    die('Нет такого умения');
                }
                
                if($skillMain->type == 1 && $skill->type == 2)
                {
                    die('Нельзя добавлять знаниям навыки');
                }

                if(!RelationSkills::model()->findByAttributes (array('id_main_skill'=>$id_main, 'id_skill'=>$skill->id)))
                {
                    $newRelation = new RelationSkills();
                    $newRelation->id_main_skill = $skillMain->id;
                    $newRelation->id_skill = $skill->id;
                    $newRelation->save(false);
                    echo true;
                }
                
            }
	}
        
	public function actionDelete($id, $id2)
	{
            $relation = RelationSkills::model()->findByAttributes(array('id_main_skill'=>$id, 'id_skill'=>$id2));
            if($relation)
                if($relation->delete())
                    echo 1;
	}

	public function loadModel($id)
	{
		$model=Skills::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Skills $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='skills-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
