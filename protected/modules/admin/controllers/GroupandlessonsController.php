<?php

class GroupandlessonsController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete','create', 'changeorderlesson'),
				'roles'=>array('editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
	public function actionCreate()
	{
		$model=new GroupAndLessons;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GroupAndLessons']))
		{
			$model->attributes=$_POST['GroupAndLessons'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
        
                // изменение порядка групп
        public function actionChangeOrderLesson($id_group)
        {
            if($_POST['id_lesson'] && $_POST['id_sibling_lesson'])
            {
                $current = GroupAndLessons::model()->findByAttributes(array('id_group'=>$id_group, 'id_lesson'=>$_POST['id_lesson']));
                $sibling = GroupAndLessons::model()->findByAttributes(array('id_group'=>$id_group, 'id_lesson'=>$_POST['id_sibling_lesson']));
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

	public function actionDelete($id_group, $id_lesson)
	{
		$model = GroupAndLessons::model()->findByAttributes(array('id_group'=>$id_group, 'id_lesson'=>$id_lesson));
                $usersLesson = UserAndLessons::model()->findAllByAttributes(array('id_group'=>$id_group, 'id_lesson'=>$id_lesson));
                if($usersLesson)
                {
                    foreach($usersLesson as $userLesson) {
                        $usersExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                        if($usersExerciseGroups)
                        {
                            foreach($usersExerciseGroups as $usersExerciseGroup)
                            {
                                UserAndExercises::model()->deleteAllByAttributes(array('id_relation'=>$usersExerciseGroup->id));
                                $usersExerciseGroup->delete();
                            }
                        }
                        UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                        $userLesson->delete();
                    }
                }
                if($model)
                    $model->delete();
	}
        
	public function loadModel($id)
	{
		$model=GroupAndLessons::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-and-lessons-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
       
}
