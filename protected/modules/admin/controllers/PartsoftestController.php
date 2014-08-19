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
				'roles'=>array('editor'),
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
                $group = GroupOfExercises::model()->findByPk($id_group);
                if($group && Courses::existCourseById($group->id_course))
                {
                    $current = PartsOfTest::model()->findByAttributes(array('id_group'=>$id_group, 'id'=>$_POST['id_criteria']));
                    $sibling = PartsOfTest::model()->findByAttributes(array('id_group'=>$id_group, 'id'=>$_POST['id_sibling_criteria']));
                    if($current && $sibling)
                    {
                        $var = $current->order;
                        $current->order = $sibling->order;
                        $sibling->order = $var;
                        if($current->save() && $sibling->save())
                        {
                            GroupOfExercises::model()->findByPk($id_group)->changeDate();
                            echo 1;
                        }
                    }
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
            if($_POST['checked'])
            {
                $part = $this->loadModel($id_part);
                $criteria = new CDbCriteria;
                $criteria->addInCondition('id_exercise', $_POST['checked']);
                $criteria->compare('id_part', $id_part);
                PartsOfTestAndExercises::model()->deleteAll($criteria);
                $part->Group->changeDate();
                $result['success'] = 1;
            }
            echo CJSON::encode($result);
	}
        
	public function actionDeleteExercise($id_part, $id_exercise)
	{
            $part = $this->loadModel($id_part);
            $model = PartsOfTestAndExercises::model()->findByAttributes(array('id_part'=>$id_part, 'id_exercise'=>$id_exercise));
            if($model)
            {
                $model->delete();
                $part->Group->changeDate();
            }
                    
	}
        
	public function actionSaveChange()
	{
            if($_POST['PartsOfTest'])
            {
                foreach($_POST['PartsOfTest'] as $id_part => $attributes)
                {
                    $model= $this->loadModel($id_part);
                    if(!$model)
                        die('Не существует такой части !');
                    $model->attributes = $attributes;
                    if($model->save())
                    {
                        $model->Group->changeDate();
                        echo 1;
                    }
                }
            }
	}
        
        public function actionUpdate($id)
	{
            $part = $this->loadModel($id);
            $this->menu[] = array('label'=>'Задания курса', 'url'=>array('/admin/exercises/index', 'id_course'=>$part->Group->id_course));

            $this->render('update',array(
                    'part'=>$part,
            ));
	}

	public function loadModel($id)
	{
		$model=PartsOfTest::model()->findByPk($id);
		if($model===null or !$model->Group or !Courses::existCourseById($model->Group->id_course))
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
