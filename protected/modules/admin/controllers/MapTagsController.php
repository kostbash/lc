<?php

class MapTagsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
                            'actions'=>array('createbyajax', 'tagsbyajax'),
                            'roles'=>array('editor'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
        public function actionCreateByAjax()
        {
            $name = trim($_POST['tagName']);
            $result = array('success'=>0);
            
            if($name)
            {
                $tag = new MapTags;
                $tag->name = $name;
                if($tag->save())
                {
                    $result['success'] = 1;
                    $result['id'] = $tag->id;
                    $result['name'] = $tag->name;
                }
                else
                {
                    $result['errors'] = print_r($tag->errors, true);
                }
            }
            echo CJSON::encode($result);
        }

        public function actionTagsByAjax()
        {
            $result = array('success'=>1);
            $result['html'] = "";
            $criteria = new CDbCriteria;
            if($_POST['term']) // если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            $criteria->limit = 10;
            $tags = MapTags::model()->findAll($criteria);
            if($tags)
            {
                foreach($tags as $tag)
                {
                    $result['html'] .= "<li data-id='$tag->id'><a href='#'>$tag->name</a></li>";
                }
            }
            else
            {
                $result['html'] = '<li><a href="#">Результатов нет</a></li>';
            }
            echo CJSON::encode($result);
        }

	public function loadModel($id)
	{
		$model=MapTags::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='map-tags-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
