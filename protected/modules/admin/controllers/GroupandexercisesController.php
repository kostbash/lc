<?php

class GroupandexercisesController extends Controller
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
                            'actions'=>array('delete','create', 'changeordergroup', 'changeorderexercise'),
                            'roles'=>array('editor'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}

        public function actionChangeOrderExercise($id_group)
        {
            if($_POST['id_exercise'] && $_POST['id_sibling_exercise'])
            {
                $current = GroupAndExercises::model()->findByAttributes(array('id_group'=>$id_group, 'id_exercise'=>$_POST['id_exercise']));
                $sibling = GroupAndExercises::model()->findByAttributes(array('id_group'=>$id_group, 'id_exercise'=>$_POST['id_sibling_exercise']));
                if($current && $sibling)
                {
                        $var = $current->order;
                        $current->order = $sibling->order;
                        $sibling->order = $var;
                        if($current->save() && $sibling->save())
                        {
                            $group = GroupOfExercises::model()->findByPk($id_group);
                            if($group)
                            {
                                $group->change_date = date('Y-m-d H:i:s');
                                $group->save(false);
                            }
                            echo 1;
                        }
                }
            }
        }
        
        public function actionChangeOrderGroup($id_lesson)
        {
            if($_POST['id_group'] && $_POST['id_sibling_group'])
            {
                $current = LessonAndExerciseGroup::model()->findByAttributes(array('id_lesson'=>$id_lesson, 'id_group_exercises'=>$_POST['id_group']));
                $sibling = LessonAndExerciseGroup::model()->findByAttributes(array('id_lesson'=>$id_lesson, 'id_group_exercises'=>$_POST['id_sibling_group']));
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
        
	public function actionDelete($id_group, $id_exercise)
	{
		$model = GroupAndExercises::model()->findByAttributes(array('id_group'=>$id_group, 'id_exercise'=>$id_exercise));
                if($model)
                {
                    $model->delete();
                    $group = GroupOfExercises::model()->findByPk($model->id_group);
                    if($group)
                    {
                        $group->change_date = date('Y-m-d H:i:s');
                        $group->save(false);
                    }
                    if($userExercisesGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$id_group)))
                    {
                        foreach($userExercisesGroups as $userExerciseGroup)
                        {
                            if($userAndExercise = UserAndExercises::model()->findByAttributes(array('id_relation'=>$userExerciseGroup->id, 'id_exercise'=>$id_exercise)))
                                $userAndExercise->delete();
                        }
                    }
                }
	}

	public function loadModel($id)
	{
		$model=GroupAndExercises::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-and-exercises-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
