<?php

class CourseandskillsController extends Controller
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
                    array('allow',
                            'actions'=>array('skillsbyajax', 'create', 'update', 'delete'),
                            'users'=>Users::Admins(),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}

	public function actionCreate()
	{
            $id_course = (int) $_POST['id_course'];
            $id_skill = (int) $_POST['id_skill'];
            $title = isset($_POST['title']);
            $skill = Skills::model()->findByPk($id_skill);
            if($id_course && $skill)
            {
                    $countSkills = CourseAndSkills::model()->countByAttributes(array('id_course'=>$id_course));
                    $model = new CourseAndSkills;
                    $model->id_course = $id_course;
                    $model->id_skill = $id_skill;
                    if($model->save()) {
                        $res['success'] = 1;
                        $res['html'] = $skill->htmlForCourse($id_course, $countSkills, true);
                    } else {
                        $res['success'] = 0;
                    }
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
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

		if(isset($_POST['CourseAndSkills']))
		{
			$model->attributes=$_POST['CourseAndSkills'];
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
	public function actionDelete($id_skill, $id_course)
	{
            $model = CourseAndSkills::model()->findByAttributes(array('id_skill'=>$id_skill, 'id_course'=>$id_course));
            if($model) {
                $model->delete();
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
            }
            echo CJSON::encode($res);
	}

	public function actionSkillsByAjax($id_course, $with_used=true)
	{
            $criteria = new CDbCriteria;

            if (isset($_POST['term']))// если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }
            if($with_used)
            {
                $course = Courses::model()->findByPk($id_course);
                if($course)
                    $criteria->addNotInCondition('id', $course->idsUsedSkills);
            }
            $criteria->addInCondition('id_course', array(0, $id_course));
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

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CourseAndSkills('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CourseAndSkills']))
			$model->attributes=$_GET['CourseAndSkills'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CourseAndSkills the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CourseAndSkills::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CourseAndSkills $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='course-and-skills-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
