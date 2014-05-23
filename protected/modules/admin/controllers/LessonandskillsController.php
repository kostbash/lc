<?php

class LessonandskillsController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','delete','create','update', 'skillsbyajax'),
				'users'=>Users::Admins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate($id_lesson)
	{
                $lesson = Lessons::model()->findByPk($id_lesson);
                if(!$lesson)
                    die("Урока не существует");
                if($_POST['id_skill'])
                { 
                    $skill = Skills::model()->findByPk($_POST['id_skill']);
                    if(!$skill)
                        die("Такого умения не сущесвует");
                    if(LessonAndSkills::model()->findByAttributes(array('id_lesson'=>$lesson->id, 'id_skill'=>$skill->id)))
                            die("У урока уже есть такое умение");
                    $model=new LessonAndSkills;
                    $model->id_lesson = $lesson->id;
                    $model->id_skill = $skill->id;
                    if($model->save())
                            echo 1;
		}
	}

	public function actionUpdate()
	{
		if(isset($_POST['LessonAndSkills']))
		{
                    foreach($_POST['LessonAndSkills'] as $key=>$lessonAndSkill)
                    {
                        $model=$this->loadModel($key);
                        $model->attributes=$lessonAndSkill;
			if($model->save())
                                    echo 1;
                    }

		}
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
                $usersLesson = UserAndLessons::model()->findAllByAttributes(array('id_lesson'=>$model->id_lesson));
                if($usersLesson)
                    foreach($usersLesson as $userLesson)
                        UserExerciseGroupSkills::model()->deleteAllByAttributes (array('id_user_and_lesson'=>$userLesson->id, 'id_skill'=>$model->id_skill));
                $model->delete();
	}

	public function loadModel($id)
	{
		$model=LessonAndSkills::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LessonAndSkills $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lesson-and-skills-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
