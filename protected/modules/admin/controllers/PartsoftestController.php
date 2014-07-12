<?php

class PartsoftestController extends Controller
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

	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete', 'changeorder', 'skillsnotidsajax', 'savechange', 'update', 'deleteexercise', 'massdeleteexercises'),
				'users'=>Users::Admins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        public function actionChangeOrder($id_group)
        {
            if($_POST['id_criteria'] && $_POST['id_sibling_criteria'])
            {
                $current = PartsOfTest::model()->findByAttributes(array('id_group'=>$id_group, 'id'=>$_POST['id_criteria']));
                $sibling = PartsOfTest::model()->findByAttributes(array('id_group'=>$id_group, 'id'=>$_POST['id_sibling_criteria']));
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

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
                if($model)
                {
                    $model->delete();
                }
                    
	}
        
	public function actionMassDeleteExercises($id_part)
	{
            $result = array('success'=>0);
            if($_POST['checked'] && PartsOfTest::model()->exists('id=:id', array('id'=>$id_part)))
            {
                $criteria = new CDbCriteria;
                $criteria->addInCondition('id_exercise', $_POST['checked']);
                $criteria->compare('id_part', $id_part);
                PartsOfTestAndExercises::model()->deleteAll($criteria);
                $result['success'] = 1;
            }
            echo CJSON::encode($result);
	}
        
	public function actionDeleteExercise($id_part, $id_exercise)
	{
		$model = PartsOfTestAndExercises::model()->findByAttributes(array('id_part'=>$id_part, 'id_exercise'=>$id_exercise));
                if($model)
                {
                    $model->delete();
                }
                    
	}
        
	public function actionSaveChange()
	{
            if($_POST['PartsOfTest'])
            {
                foreach($_POST['PartsOfTest'] as $id_part => $attributes)
                {
                    $model= PartsOfTest::model()->findByPk($id_part);
                    if(!$model)
                        die('Не существует такой части !');
                    $model->attributes = $attributes;
                    if($model->save())
                    {
                        echo 1;
                    }
                }
            }
	}
        
        public function actionUpdate($id)
	{
            $part = $this->loadModel($id);

            $this->render('update',array(
                    'part'=>$part,
            ));
	}

	public function loadModel($id)
	{
		$model=PartsOfTest::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
