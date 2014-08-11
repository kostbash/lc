<?php

class GeneratorswordsController extends Controller
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
                            'actions'=>array('create','delete', 'updatebyajax', 'createbyajax', 'SWFupload', 'removeImage'),
                            'roles'=>array('editor'),
                    ),
                    array('deny',  // deny all users
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new GeneratorsWords;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeneratorsWords']))
		{
			$model->attributes=$_POST['GeneratorsWords'];
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

		if(isset($_POST['GeneratorsWords']))
		{
			$model->attributes=$_POST['GeneratorsWords'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

        public function actionUpdateByAjax()
	{
            $result = array('success'=>0);
            if(isset($_POST['GeneratorsWords']))
            {
                foreach ($_POST['GeneratorsWords'] as $id_word => $attributes)
                {
                    $model = GeneratorsWords::model()->findByPk($id_word);
                    if($model)
                    {
                        $model->attributes=$attributes;
                        if($model->save())
                        {
                            if($attributes['TagsIds'])
                            {
                                // сначала удаляем все теги которых нет в массиве
                                $criteria = new CDbCriteria;
                                $criteria->condition = '`id_word` = :id_word';
                                $criteria->params['id_word'] = $id_word;
                                $criteria->addNotInCondition('id_tag', $attributes['TagsIds']);
                                GeneratorsWordsTags::model()->deleteAll($criteria);
                                $wordsTags = new GeneratorsWordsTags();
                                
                                // добавляем теги
                                foreach($attributes['TagsIds'] as $id_tag)
                                {
                                    // проверяем принадлежит ли тег тому же словарю что и слово
                                    if(GeneratorsTags::model()->exists("id=:id_tag AND id_dictionary=:id_dictionary", array('id_tag'=>$id_tag, 'id_dictionary'=>$model->id_dictionary)))
                                    {
                                        if(!GeneratorsWordsTags::model()->exists("id_word=:id_word AND id_tag=:id_tag", array('id_word'=>$id_word, 'id_tag'=>$id_tag)))
                                        {
                                            $wordsTags->id_word = $id_word;
                                            $wordsTags->id_tag = $id_tag;  
                                            $wordsTags->save();
                                            $wordsTags->id = false;
                                            $wordsTags->isNewRecord = true;
                                        }
                                    }
                                }
                            } else {
                                // если массив пуст значит удаляем все теги
                                GeneratorsWordsTags::model()->deleteAllByAttributes(array('id_word'=>$id_word));
                            }
                           $result['success'] = 1;
                        } else {
                            $result['errors'] = print_r($model->errors, true);
                        }
                    }
                }
            }
            echo CJSON::encode($result);
	}
        
        public function actionCreateByAjax()
        {
            $id_dict = (int) $_POST['id_dict'];
            $result = array('success'=>0);
            if($_POST['GeneratorsWords'] && GeneratorsDictionaries::model()->exists('id=:id_dict', array('id_dict'=>$id_dict)))
            {
                $word = new GeneratorsWords;
                $word->attributes = $_POST['GeneratorsWords'];
                $word->id_dictionary = $id_dict;
                if($word->save())
                {
                    if($_POST['GeneratorsWords']['TagsIds'])
                    {
                        $wordsTags = new GeneratorsWordsTags();
                        foreach($_POST['GeneratorsWords']['TagsIds'] as $id_tag)
                        {
                            if(!GeneratorsWordsTags::model()->exists("id_word=:id_word AND id_tag=:id_tag", array('id_word'=>$word->id, 'id_tag'=>$id_tag)))
                            {
                                $wordsTags->id_word = $word->id;
                                $wordsTags->id_tag = $id_tag;  
                                $wordsTags->save();
                                $wordsTags->id = false;
                                $wordsTags->isNewRecord = true;
                            }
                        }
                    }
                    $result['success'] = 1;
                } else {
                    $result['errors'] = print_r($word->errors, true);
                }
            }
            echo CJSON::encode($result);
        }
        
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
	}
        
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('GeneratorsWords');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GeneratorsWords('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GeneratorsWords']))
			$model->attributes=$_GET['GeneratorsWords'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GeneratorsWords the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GeneratorsWords::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GeneratorsWords $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='generators-words-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionSWFUpload($id_word)
        {
            if ($_FILES['ImportFile'])
            {

                if ($file = CUploadedFile::getInstanceByName("ImportFile"))
                { 
                    $fname = substr(md5($file->name.time()),0,10);
                    $file->saveAs(Yii::app()->params['WordsImagesPath']."/".$fname.'.'.$file->extensionName);
                    if($word = GeneratorsWords::model()->findByPk($id_word))
                    {
                        $word->image = $fname.'.'.$file->extensionName;
                        $word->save(false);
                        
                        echo $this->processOutput($word->imageLinkWithUpload);
                    }
                }
            }
        }
        
        public function actionRemoveImage($id_word)
        {
            if($word = GeneratorsWords::model()->findByPk($id_word))
            {
                $word->image = null;
                $word->save(false);
                echo $word->imageLinkWithUpload;
            }
        }
}
