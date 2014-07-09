<?php

class SkillsController extends Controller
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
				'actions'=>array('index','delete','create','update', 'skillsbyidajax', 'addcourseskill', 'gethtmlmini'),
				'users'=>Users::Admins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate()
	{
		$model=new Skills;
		if(isset($_POST['Skills']))
		{
                    $model->attributes=$_POST['Skills'];
                    if($model->save())
                    {
                        if(isset($_REQUEST['Skills']['fromcourse']) && $_REQUEST['Skills']['fromcourse'] )
                            echo $model->id;
                        else
                            echo true;
                    }

		}
	}
        
	public function actionGetHtmlMini()
	{
            $id = (int) $_POST['id'];
            $result = array();
            if($id)
            {
		$model= $this->loadModel($id);
                $result['success'] = 1;
                $result['html'] = $this->renderPartial('_skill_mini', array('model'=>$model), true);
            } else {
                $result['success'] = 0;
            }
            echo CJSON::encode($result);
	}
        
	public function actionAddCourseSkill($id_course)
	{
            if(isset($_POST['Skills']) && Courses::model()->exists('id=:id_course', array('id_course'=>$id_course)))
            {
                $res = array();
                $model=new Skills;
                $model->attributes=$_POST['Skills'];
                $model->type=2; //навык
                $model->id_course = $id_course;
                if($model->save())
                {
                    $countSkills = CourseAndSkills::model()->countByAttributes(array('id_course'=>$id_course));
                    $courseAndSkills = new CourseAndSkills;
                    $courseAndSkills->id_course = $id_course;
                    $courseAndSkills->id_skill = $model->id;
                    $courseAndSkills->save();
                    $res['success'] = 1;
                    $res['html'] = $model->htmlForCourse($id_course, $countSkills, true);
                    $res['id']=$model->id;
                    $res['name']=$model->name;
                } else {
                    $res['success'] = 0;
                }
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
	}
        
	public function actionSkillsByIdAjax()
	{
            if (isset($_POST['id']))
            {
                $mainSkill = Skills::model()->findByPk($_POST['id']);
                if($mainSkill)
                {
                    $criteria = new CDbCriteria;
                    if(isset($_POST['term']))
                    {
                        $criteria->condition = '`name` LIKE :name';
                        $criteria->params['name'] = '%' . $_POST['term'] . '%';
                    }
                    $criteria->addNotInCondition('id', $mainSkill->idsUsed);
                    $criteria->limit = 10;
                    if($mainSkill->type == 1)
                        $criteria->compare('type',1);
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
            }
	}

	public function actionUpdate()
	{
            if(isset($_POST['Skills']))
            {
                foreach($_POST['Skills'] as $id => $attributes)
                {
                        $model = $this->loadModel($id);
                        $model->attributes=$attributes;
                        if($model->save())
                                echo true;
                        else
                            echo 'Название не может быть пустым';
                }
            }
	}

	public function actionDelete($id)
	{
            $model = $this->loadModel($id);
            $msg = "Невозможно удалить умение. Используется :";
            $delete = 1;
            if($model->countCourses) {
                $msg .= "\nВ курсах : \n";
                foreach($model->Courses as $course)
                    $msg .= $course->name."\n";
                $delete = 0;
            }
            if($model->countExercisesGroups) {
                $msg .= "\nВ уроках : \n";
                foreach($model->ExercisesGroups as $exerciseGroup)
                    $msg .= $exerciseGroup->name."\n";
                $delete = 0;
            }
            if($model->countExercises) {
                $msg .= "\nВ заданиях :\n";
                foreach($model->Exercises as $exercise)
                    $msg .= $exercise->question."\n";
                $delete = 0;
            }
            if($model->countTopSkills) {
                $msg .= "\nВ других умениях : \n";
                foreach($model->TopSkills as $topSkill)
                    $msg .= $topSkill->name."\n";
                $delete = 0;
            }
            if($delete)
            {
                $model->delete();
                echo 1;
            } else {
                echo $msg;
            }
	}

	public function actionIndex($id_course=null)
	{
            $model=new Skills('search');
            $model->unsetAttributes();  // clear any default values
            if($id_course)
                $course = Courses::model()->findByPk($id_course);
            if(isset($_GET['Skills']))
                    $model->attributes=$_GET['Skills'];
            if($course)
                $model->id_course = $course->id;
            else
                $model->id_course = 0;
            $id_course = $course ? $course->id : 0;
            $this->render('index',array(
                    'model'=>$model,
                    'course'=>$course,
                    'id_course'=> $id_course,
            ));
	}

	public function loadModel($id)
	{
		$model=Skills::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='skills-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
