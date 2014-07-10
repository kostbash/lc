<?php

class GeneratorstagsController extends Controller
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
                            'actions'=>array('create','delete', 'listdatabydictionary', 'tagsofdictionary', 'createbyajax'),
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
		$model=new GeneratorsTags;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeneratorsTags']))
		{
			$model->attributes=$_POST['GeneratorsTags'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

        public function actionListDataByDictionary() {
            $id_dict = (int) $_POST['id_dict'];
            $result = array('success'=>0);
            $tags = GeneratorsTags::model()->findAllByAttributes(array('id_dictionary'=>$id_dict));
            if($tags)
            {
                $result['success'] = 1;
                $result['html'] = '<option value="">Показать все теги</option>';
                foreach($tags as $tag)
                {
                    $result['html'] .= $tag->asOption;
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionCreateByAjax()
        {
            $id_dict = (int) $_POST['id_dict'];
            $name = trim($_POST['name']);
            $result = array('success'=>0);
            
            if($name && GeneratorsDictionaries::model()->exists('id=:id_dict', array('id_dict'=>$id_dict)))
            {
                $tag = new GeneratorsTags;
                $tag->name = $name;
                $tag->id_dictionary = $id_dict;
                if($tag->save())
                {
                    $result['success'] = 1;
                    $result['html'] = $tag->asOption;
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionTagsOfDictionary()
        {
            $id_dict = (int) $_POST['id_dict'];
            $result = array('success'=>1);
            if($id_dict)
            {
                $result['html'] = "";
                $criteria = new CDbCriteria;
                if($_POST['term']) // если переданы символы
                {
                    $criteria->condition = '`name` LIKE :name';
                    $criteria->params['name'] = '%' . $_POST['term'] . '%';
                }
                $criteria->compare('id_dictionary', $id_dict);
                $criteria->limit = 10;
                $tags = GeneratorsTags::model()->findAll($criteria);
                if($tags)
                {
                    foreach($tags as $tag)
                    {
                        $result['html'] .= "<li data-id='$tag->id'><a href='#'>$tag->name</a></li>";
                    }
                } else {
                    $result['html'] = '<li><a href="#">Результатов нет</a></li>';
                }
            } else {
                $result['html'] = '<li><a href="#">Результатов нет</a></li>';
            }
            echo CJSON::encode($result);
        }
        
	public function actionSkillsByAjax($id_exercise)
	{
            $exercise = $this->loadModel($id_exercise);
            
            $criteria = new CDbCriteria;

            if (isset($_POST['term']))// если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            $criteria->addNotInCondition('id', $exercise->idsUsedSkills);
            $criteria->limit = 10;
            $skills = Skills::model()->findAll($criteria);
            $res = '';
            foreach ($skills  as $skill)
            {
                $res .= "<li data-id='$skill->id'><a href='#'>$skill->name</a></li>";
            }
            if($res=='')
                $res = '<li><a href="#">Результатов нет</a></li>';
            echo $res;
	}
        
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeneratorsTags']))
		{
			$model->attributes=$_POST['GeneratorsTags'];
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
		$dataProvider=new CActiveDataProvider('GeneratorsTags');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GeneratorsTags('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GeneratorsTags']))
			$model->attributes=$_GET['GeneratorsTags'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GeneratorsTags the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GeneratorsTags::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GeneratorsTags $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='generators-tags-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
