<?php

class GroupofexercisesController extends Controller
{
	public $layout='/layouts/column2';

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
			array('allow',
				'actions'=>array('delete','create','update', 'updatebyajax', 'removeskill', 'addskill', 'createincourse', 'skillsbyajax', 'RemoveSkillByGroup'),
				'roles'=>array('editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate($id_lesson)
	{
            
		if(isset($_POST['GroupOfExercises']))
		{
                    if(!$_POST['GroupOfExercises']['type'])
                        die('Выберите тип группы заданий');
                    $lesson = Lessons::model()->findByPk($id_lesson);
                    if(!$lesson)
                        die('Нет такого урока');
                    $model=new GroupOfExercises;
                    $model->attributes=$_POST['GroupOfExercises'];
                    if($model->save()) {
                        $lessonAndExerciseGroup = new LessonAndExerciseGroup();
                        $lessonAndExerciseGroup->id_group_exercises = $model->id;
                        $lessonAndExerciseGroup->id_lesson = $id_lesson;
                        $lessonAndExerciseGroup->order = LessonAndExerciseGroup::maxValueOrder($id_lesson);
                        if($lessonAndExerciseGroup->save())
                            echo 1;
                    }		
		}
	}
        
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                $skills = new GroupExerciseAndSkills('search');
                $skills->id_group = $model->id;
                
                $this->menu[] = array('label'=>'Задания курса', 'url'=>array('/admin/exercises/index', 'id_course'=>$model->id_course));

		$this->render('update',array(
			'exerciseGroup'=>$model,
                        'skills'=>$skills,
		));
	}

	public function actionDelete($id)
	{
            $model = $this->loadModel($id);
            if($model->delete())
                echo 1;
	}

	public function loadModel($id)
	{
		$model=GroupOfExercises::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        public function actionUpdateByAjax() {
            if($_POST['GroupOfExercises'])
            {
                foreach($_POST['GroupOfExercises'] as $id_group => $attributes)
                {
                    $model=$this->loadModel($id_group);
                    if($attributes['name'])
                        $model->name = trim($attributes['name']);
                    
                    if($attributes['type'])
                    {
                        $usersExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$id_group));
                        foreach($usersExerciseGroups as $usersExerciseGroup)
                        {
                            if($model->type==1)
                            {
                                UserAndExercises::model()->deleteAllByAttributes(array('id_relation'=>$usersExerciseGroup->id));
                            } else {
                                UserExerciseGroupSkills::model()->deleteAllByAttributes(array('id_test_group'=>$usersExerciseGroup->id));
                            }
                            $usersExerciseGroup->number_right = NULL;
                            $usersExerciseGroup->number_all = NULL;
                            $usersExerciseGroup->save();
                        }
                        
                        if($model->PartsOfTest)
                        {
                            foreach($model->PartsOfTest as $partsOfTest)
                            {
                                $partsOfTest->delete();
                            }
                        }
                        
                        GroupAndExercises::model()->deleteAllByAttributes(array('id_group'=>$id_group));
                        
                        $model->type = $attributes['type'];
                        $res['needUpdate'] = 1;
                    }
                    
                    if($attributes['Skills'])
                    {
                        foreach($attributes['Skills'] as $id_skill => $attrSkill)
                            $skill = GroupExerciseAndSkills::model()->findByAttributes(array('id_group'=>$id_group, 'id_skill'=>$id_skill));
                            if($skill)
                            {
                                $skill->pass_percent = $attrSkill['pass_percent'];
                                $skill->save();
                            }
                    }
                    if($model->save()) {
                        $res['success'] = 1;
                    } else {
                        $res['success'] = 0;
                    }
                }
                echo CJSON::encode($res);
            }
        }
        
        public function actionAddSkill() {
            $id_group = (int) $_POST['id_group'];
            $id_skill = (int) $_POST['id_skill'];
            $group = GroupOfExercises::model()->findByPk($id_group);
            if($group && $id_skill)
            {
                $model = GroupExerciseAndSkills::model()->findByAttributes(array('id_group'=>$id_group, 'id_skill'=>$id_skill));
                if(!$model)
                {
                    $model = new GroupExerciseAndSkills;
                    $model->id_group = $id_group;
                    $model->id_skill = $id_skill;
                    $model->pass_percent = 0;
                    if($model->save()) {
                        $model = new CourseAndSkills;
                        $model->id_course = GroupOfExercises::model()->findByAttributes(array('id'=>$id_group))->id_course;
                        $model->id_skill = $id_skill;
                        $model->save();
                        
                        $res['success'] = 1;
                        $res['html'] = $group->htmlForCourse;
                    } else {
                        $res['success'] = 0;
                        $res['message'] = 'Сохранение не произошло';
                    }
                } else {
                    $res['success'] = 0;
                    $res['message'] = 'Такое умение уже есть !';
                }
            }
             echo CJSON::encode($res);
        }
        
        public function actionRemoveSkill($id) {
            $model= GroupExerciseAndSkills::model()->findByPk($id);
            $model->delete();
        }

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-of-exercises-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
	public function actionSkillsByAjax($id)
	{
            $group = GroupOfExercises::model()->findByPk($id);
            
            $criteria = new CDbCriteria;
            if (isset($_POST['term']))// если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            $criteria->addNotInCondition('id', $group->idsUsedSkills);
            $criteria->addInCondition('id_course', array(0, $group->id_course));
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
        
        public function actionCreateInCourse($id_course)
	{
            $name = trim($_POST['name']);
            $type = (int) $_POST['type'];
            if($name && $type && Courses::model()->findByPk($id_course))
            {
                $model=new GroupOfExercises;
                $model->name = $name;
                $model->type = $type;
                $model->id_course = $id_course;
                if($model->save())
                {
                    $courseAndGroupExercise = new CoursesAndGroupExercise;
                    $courseAndGroupExercise->id_course = $id_course;
                    $courseAndGroupExercise->id_group = $model->id;
                    $courseAndGroupExercise->order = CoursesAndGroupExercise::maxValueOrder($id_course);
                    if($courseAndGroupExercise->save())
                    {
                        $res['success'] = 1;
                        $res['html'] = $model->htmlForCourse;
                        echo CJSON::encode($res, true);
                    }
                }
            }
	}
        
        
        public function actionRemoveSkillByGroup($id_group, $id_skill)
        {
            if($gof = GroupExerciseAndSkills::model()->findByAttributes(array('id_group'=>$id_group, 'id_skill'=>$id_skill)))
            {
                if($gof->delete()) {
                        $res['success'] = 1;
                        $res['html'] = '';
                    } else {
                        $res['success'] = 0;
                        $res['message'] = 'Удаление не произошло';
                    }
            }
            else
            {
                $res['success'] = 0;
                $res['message'] = 'Удаление не произошло: такого умения нет в блоке';
            }
            echo CJSON::encode($res);
        }
}
