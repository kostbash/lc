<?php

class ExercisesController extends Controller
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
                            'actions'=>array('create','update', 'updatebyajax', 'delete','index', 'savechange', 'skillsbyajax', 'skillsnotidsajax', 'skillsbyidsajax', 'createfromgroup','SWFUpload','massdelete', 'gethtmlvisual', 'gethtmlvariant'),
                            'roles'=>array('editor'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}

	public function actionCreate($id_type)
	{
            $model=new Exercises;
            $id_group = (int) $_GET['id_group'];
            $id_part = (int) $_GET['id_part'];
            $id_visual = (int) $_GET['id_visual'];
            // не даем возможность добавлять тесту тип контент т.к. у него нету ответа
            if($id_type==4 && $id_part)
                $this->redirect(array('/admin/exercises/index'));
            $model->id_type = ExercisesTypes::model()->exists('id=:id', array('id'=>$id_type)) ? $id_type : Exercises::$defaultType;
            $model->id_visual = ExercisesVisuals::model()->exists('id=:id AND id_type=:id_type', array('id'=>$id_visual, 'id_type'=>$model->id_type)) ? $id_visual : null;
            $model->course_creator_id = 0;
            if($id_part) {
                $part = PartsOfTest::model()->findByPk($id_part);
                $groupExercise = $part->Group;
            } elseif($id_group) {
                $groupExercise = GroupOfExercises::model()->findBypk($id_group);
            }

            if($groupExercise)
            {
                if(!Courses::existCourseById($groupExercise->id_course))
                    throw new CHttpException(404,'The requested page does not exist.');
            }
            
            if(isset($_POST['Exercises']))
            {
                //CVarDumper::dump($_POST, 5, true); die;
                $model->attributes = $_POST['Exercises'];
                $model->course_creator_id = $groupExercise && $groupExercise->id_course ? $groupExercise->id_course : 0;
                $model->change_date = date('Y-m-d H:i:s');
                if($model->save())
                {
                    if($_POST['Skills']['ids'])
                    {
                        $exerciseSkills = new ExerciseAndSkills();
                        foreach($_POST['Skills']['ids'] as $id_skill)
                        {
                            $exerciseSkills->id_exercise = $model->id;
                            $exerciseSkills->id_skill = $id_skill;
                            $exerciseSkills->save();
                            $exerciseSkills->isNewRecord = true;
                            $exerciseSkills->id = false;
                        }
                    }
                    
                    if($id_visual==9)
                    {
                        $question = new ExercisesQuestions;
                        $question->attributes = $_POST['Exercises']['questions'][0];
                        $question->id_exercise = $model->id;
                        if($question->save())
                        {
                            preg_match_all('#sp(\d+)#ui', $question->text, $matches);
                            $numberSpaces = array_unique($matches[1]);
                            if($_POST['Exercises']['answers'])
                            {
                                foreach($_POST['Exercises']['answers'] as $answerAttr)
                                {
                                    // проверяем что пробел действительно существует
                                    if(in_array($answerAttr['number_space'], $numberSpaces))
                                    {
                                        $exercisesAnswers = new ExercisesListOfAnswers;
                                        $exercisesAnswers->attributes = $answerAttr;
                                        $exercisesAnswers ->id_exercise = $model->id;
                                        $exercisesAnswers ->id_question = $question->id;
                                        $exercisesAnswers->save();
                                    }
                                }
                            }
                        }
                    }
                    elseif($id_visual==8)
                    {
                        $question = new ExercisesQuestions;
                        $question->attributes = $_POST['Exercises']['questions'][0];
                        $question->id_exercise = $model->id;
                        if($question->save())
                        {
                            preg_match_all('#sp(\d+)#ui', $question->text, $matches);
                            $numberSpaces = array_unique($matches[1]);
                            
                            if($_POST['Exercises']['answers'])
                            {
                                $i = 0;
                                foreach($_POST['Exercises']['answers'] as $answerAttr)
                                {
                                    if(!$numberSpaces[$i])
                                        break;
                                    $exercisesAnswers = new ExercisesListOfAnswers;
                                    $exercisesAnswers->attributes = $answerAttr;
                                    $exercisesAnswers ->id_exercise = $model->id;
                                    $exercisesAnswers ->id_question = $question->id;
                                    $exercisesAnswers ->is_right = 1;
                                    $exercisesAnswers ->number_space = $numberSpaces[$i++];
                                    $exercisesAnswers->save();
                                }
                            }
                        }
                    }
                    else 
                    {
                        if($_POST['Exercises']['answers'])
                        {
                            foreach($_POST['Exercises']['answers'] as $answerAttr)
                            {
                                $exercisesAnswers = new ExercisesListOfAnswers;
                                $exercisesAnswers->attributes = $answerAttr;
                                $exercisesAnswers ->id_exercise = $model->id;
                                $exercisesAnswers->save();
                            }
                        }
                    }
                    
                    if($_POST['Exercises']['Comparisons'])
                    {
                        foreach($_POST['Exercises']['Comparisons'] as $comparison)
                        {
                            $comparisonAttr = array();
                            foreach($comparison as $nameAttr => $answerAttr)
                            {
                                $exercisesAnswer = new ExercisesListOfAnswers;
                                $exercisesAnswer->attributes = $answerAttr;
                                $exercisesAnswer->is_right = 1;
                                $exercisesAnswer ->id_exercise = $model->id;
                                $exercisesAnswer->save();
                                $comparisonAttr[$nameAttr] = $exercisesAnswer->id;
                            }
                            $newComparison = new ExercisesComparisons;
                            $newComparison->attributes = $comparisonAttr;
                            $newComparison->id_exercise = $model->id;
                            $newComparison->save();
                            unset($comparisonAttr);
                        }
                    }
                    
                    if($groupExercise)
                    {
                        $groupExercise->change_date = date('Y-m-d H:i:s');
                        $groupExercise->save(false);
                    }
                    
                    // добавляем задание в группу
                    if($id_part && $part)
                    {
                        $partExercises = new PartsOfTestAndExercises;
                        $partExercises->id_part = $id_part;
                        $partExercises->id_exercise = $model->id;
                        $partExercises->save();
                        $this->redirect(array('/admin/partsoftest/update', 'id'=>$id_part));
                    }
                    // добавляем задание в часть теста
                    elseif($id_group && $groupExercise)
                    {
                        $groupAndExercise = new GroupAndExercises();
                        $groupAndExercise->id_group = $id_group;
                        $groupAndExercise->id_exercise = $model->id;
                        $groupAndExercise->order = GroupAndExercises::maxValueOrder($id_group);
                        $groupAndExercise->save();
                        $this->redirect(array('/admin/groupofexercises/update', 'id'=>$id_group));
                    } else {
                        $this->redirect(array('/admin/exercises/index'));
                    }
                }
            }
            
            $this->render('create', array(
                'model'=>$model,
            ));
	}
        
	public function actionUpdate($id)
	{
            $model = $this->loadModel($id);
            $id_group = (int) $_GET['id_group'];
            $id_part = (int) $_GET['id_part'];
            if($id_part) {
                $part = PartsOfTest::model()->findByPk($id_part);
            } elseif($id_group) {
                $groupExercise = GroupOfExercises::model()->findBypk($id_group);
            }
            
            if(isset($_POST['Exercises']))
            {
                //CVarDumper::dump($_POST, 7, true); die;
                $model->attributes = $_POST['Exercises'];
                $model->change_date = date('Y-m-d H:i:s');
                if($model->save())
                {
                    if($_POST['Skills']['ids'])
                    {
                        foreach($model->ExerciseAndSkills as $eSkill) $eSkill->delete();
                        $exerciseSkills = new ExerciseAndSkills();
                        foreach($_POST['Skills']['ids'] as $id_skill)
                        {
                            $exerciseSkills->id_exercise = $model->id;
                            $exerciseSkills->id_skill = $id_skill;
                            $exerciseSkills->save();
                            $exerciseSkills->isNewRecord = true;
                            $exerciseSkills->id = false;
                        }
                    }
                    
                    if($model->id_visual==9)
                    {
                        $question = $model->Questions[0];
                        $question->attributes = $_POST['Exercises']['questions'][0];
                        if($question->save())
                        {
                            preg_match_all('#sp(\d+)#ui', $question->text, $matches);
                            $numberSpaces = $matches[1];
                            if($_POST['Exercises']['answers'])
                            {
                                foreach($model->Answers as $eAnswer) $eAnswer->delete();
                                foreach($_POST['Exercises']['answers'] as $answerAttr)
                                {
                                    // проверяем что пробел действительно существует
                                    if(in_array($answerAttr['number_space'], $numberSpaces))
                                    {
                                        $exercisesAnswers = new ExercisesListOfAnswers;
                                        $exercisesAnswers->attributes = $answerAttr;
                                        $exercisesAnswers ->id_exercise = $model->id;
                                        $exercisesAnswers ->id_question = $question->id;
                                        $exercisesAnswers->save();
                                    }
                                }
                            }
                        }
                    } 
                    elseif($model->id_visual==8)
                    {
                        $question = $model->Questions[0];
                        $question->attributes = $_POST['Exercises']['questions'][0];
                        if($question->save())
                        {
                            preg_match_all('#sp(\d+)#ui', $question->text, $matches);
                            $numberSpaces = array_unique($matches[1]);
                            if($_POST['Exercises']['answers'])
                            {
                                $i = 0;
                                foreach($model->Answers as $eAnswer) $eAnswer->delete();
                                foreach($_POST['Exercises']['answers'] as $answerAttr)
                                {
                                    if(!$numberSpaces[$i])
                                        break;
                                    $exercisesAnswers = new ExercisesListOfAnswers;
                                    $exercisesAnswers->attributes = $answerAttr;
                                    $exercisesAnswers->id_exercise = $model->id;
                                    $exercisesAnswers->id_question = $question->id;
                                    $exercisesAnswers->is_right = 1;
                                    $exercisesAnswers->number_space = $numberSpaces[$i++];
                                    $exercisesAnswers->save();
                                }
                            }
                        }
                    }
                    else
                    {
                        if($_POST['Exercises']['answers'])
                        {

                            foreach($model->Answers as $eAnswer) $eAnswer->delete();
                            foreach($_POST['Exercises']['answers'] as $answerAttr)
                            {
                                $exercisesAnswers = new ExercisesListOfAnswers;
                                $exercisesAnswers->attributes = $answerAttr;
                                $exercisesAnswers ->id_exercise = $model->id;
                                $exercisesAnswers->save();
                            }
                        }
                    }
                    
                    if($_POST['Exercises']['Comparisons'])
                    {
                        foreach($model->Comparisons as $eComparison) $eComparison->delete();
                        foreach($_POST['Exercises']['Comparisons'] as $comparison)
                        {
                            $comparisonAttr = array();
                            foreach($comparison as $nameAttr => $answerAttr)
                            {
                                $exercisesAnswer = new ExercisesListOfAnswers;
                                $exercisesAnswer->attributes = $answerAttr;
                                $exercisesAnswer->is_right = 1;
                                $exercisesAnswer ->id_exercise = $model->id;
                                $exercisesAnswer->save();
                                $comparisonAttr[$nameAttr] = $exercisesAnswer->id;
                            }
                            $newComparison = new ExercisesComparisons;
                            $newComparison->attributes = $comparisonAttr;
                            $newComparison->id_exercise = $model->id;
                            $newComparison->save();
                            unset($comparisonAttr);
                        }
                    }
                    
                    // добавляем задание в группу
                    if($part)
                    {
                        $this->redirect(array('/admin/partsoftest/update', 'id'=>$id_part));
                    }
                    // добавляем задание в часть теста
                    elseif($groupExercise)
                    {
                        $this->redirect(array('/admin/groupofexercises/update', 'id'=>$id_group));
                    } else {
                        $this->redirect(array('/admin/exercises/index'));
                    }
                }
                print_r($model->errors);die;
                $this->redirect(Yii::app()->request->urlReferrer);   
            }
            
            $this->render('update', array(
                'model'=>$model,
            ));
	}
        
        public function actionGetHtmlVariant() {
            $index = (int) $_POST['index'];
            $id_visual = (int) $_POST['id_visual'];
            $result['success'] = 1;
            $result['html'] = @$this->renderPartial("visualizations/{$id_visual}_variant", array('index'=> $index), true);
            echo CJSON::encode($result);
        }
        
        public function actionGetHtmlVisual() {
            $id_visual = (int) $_POST['id_visual'];
            $result = array();
            if($id_visual && ExercisesVisuals::model()->exists('id=:id', array('id'=>$id_visual)))
            {
                $result['success'] = 1;
                $result['html'] = $this->renderPartial("visualizations/{$id_visual}", array('model'=> new Exercises), true);
            } else {
                $result['success'] = 0;
            }
            echo CJSON::encode($result);
        }
        
	public function actionUpdateByAjax()
	{
            if(isset($_POST['Exercises']))
            {
                foreach ($_POST['Exercises'] as $id => $attributes)
                {
                    $model = $this->loadModel($id);
                    if(!$model)
                        die('Нет такого задания');
                    $model->attributes=$attributes;
                    $model->change_date = date('Y-m-d H:i:s');
                    if($model->save())
                            echo 1;
                }
            }
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
                if($model->canDelete)
                {
                    if($model->delete())
                        echo 1;
                } else {
                    $res = "Удаление невозможно. Задание используется в группах :\n";
                    foreach($model->ExercisesGroup as $exerciseGroup)
                    {
                        $res .= "$exerciseGroup->name\n";
                    }
                    echo $res;
                }
	}
        
        
	public function actionMassDelete()
	{
            if($_POST['checked'])
            {
                $res = '';
                foreach($_POST['checked'] as $id)
                {
                    $exercise = $this->loadModel($id);
                    if($exercise->canDelete)
                    {
                        $exercise->delete();
                    } else {
                        $res .= "Задание '$exercise->condition' используется в группах :\n";
                        foreach($exercise->ExercisesGroup as $exerciseGroup)
                        {
                                $res .= "$exerciseGroup->name \n";
                        }
                    }
                }
                if($res)
                    echo $res;
                else
                    echo 1;
            }
	}

	public function actionIndex()
	{
		$model=new Exercises('search');
		$model->unsetAttributes();
                
                $id_group = (int) $_GET['id_group'];
                $id_part = (int) $_GET['id_part'];
                $id_course = (int) $_GET['id_course'];
                $local = (int) $_GET['local'];
                
                if($id_group)
                {
                    $groupExercise = GroupOfExercises::model()->findBypk($id_group);
                    
                } elseif($id_part)
                {
                    $part = PartsOfTest::model()->findByPk($id_part);
                    $groupExercise = $part->Group;
                }
                
                if($groupExercise)
                {
                    if(!Courses::existCourseById($groupExercise->id_course))
                        throw new CHttpException(404,'The requested page does not exist.');
                }
                
                if($id_course)
                {
                    if(!$course = Courses::CourseById($id_course))
                        throw new CHttpException(404,'The requested page does not exist.');
                    $model->course_creator_id = $id_course;
                } else
                    $model->course_creator_id = $local && $groupExercise && $groupExercise->id_course ? $groupExercise->id_course : 0;
                
		if(isset($_POST['filter'])) {
                    $model->attributes=$_POST['Exercises'];
                    unset($_SESSION['Exercises']);
                    $_SESSION['Exercises'] = $_POST['Exercises'];
                    $_GET['filter'] = 1;
                } elseif($_GET['filter'] && $_SESSION['Exercises']) {
                    $model->attributes=$_SESSION['Exercises'];
                }

                if($groupExercise && $_POST['checked'])
                {
                    // если тип группы заданий упражнение
                    if($groupExercise->type == 1)
                    {
                        $groupAndExercise = new GroupAndExercises();
                        foreach($_POST['checked'] as $id_exercise)
                        {
                            if(!GroupAndExercises::model()->exists("id_group=:id_group AND id_exercise=:id_exercise", array('id_group'=>$id_group, 'id_exercise'=>$id_exercise)))
                            {
                                $groupAndExercise->id_group = $id_group;
                                $groupAndExercise->id_exercise = $id_exercise;
                                $groupAndExercise->order = GroupAndExercises::maxValueOrder($id_group);
                                $groupAndExercise->save(false);
                                $groupAndExercise->id = false;
                                $groupAndExercise->isNewRecord = true;
                            }
                        }
                    }
                    // если создаем новую часть для теста
                    elseif($groupExercise->type == 2)
                    {
                        if(!$part)
                        {
                            $part = new PartsOfTest;
                            $part->id_group = $id_group;
                            $part->order = PartsOfTest::maxValueOrder($id_group);
                            $part->save();
                        }
                        $partExercises = new PartsOfTestAndExercises;
                        foreach($_POST['checked'] as $id_exercise)
                        {
                            if($id_part)
                            {
                                $hasExercise = PartsOfTestAndExercises::model()->exists("id_part=:id_part AND id_exercise=:id_exercise", array('id_part'=>$id_part, 'id_exercise'=>$id_exercise));
                                if($hasExercise)
                                    continue;
                            }
                            // проверяем, что заданию нужен ответ
                            if(Exercises::model()->exists('id=:id AND id_visual<>4', array('id'=>$id_exercise)))
                            {
                                $partExercises->id_part = $part->id;
                                $partExercises->id_exercise = $id_exercise;
                                $partExercises->save(false);
                                $partExercises->id = false;
                                $partExercises->isNewRecord = true;
                            }
                        }
                    }
                    $groupExercise->change_date = date('Y-m-d H:i:s');
                    $groupExercise->save(false);
                    if($id_part)
                        $this->redirect(array('/admin/partsoftest/update', 'id'=>$id_part));
                    else
                        $this->redirect(array('/admin/groupofexercises/update', 'id'=>$id_group));
                }
                
		$this->render('index',array(
			'model'=>$model,
                        'course'=>$course,
                        'group' => $groupExercise,
		));
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
        
	public function actionSkillsNotIdsAjax()
	{
            $criteria = new CDbCriteria;

            if ($_POST['term'])
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            if($_POST['skillsIds'])
                $criteria->addNotInCondition('id', $_POST['skillsIds']);
            
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
        
	public function actionSkillsByIdsAjax($id_group)
	{
            $criteria = new CDbCriteria;
            $group = GroupOfExercises::model()->findByPk($id_group);
            $res = '';
            if (isset($_POST['term']))// если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            if($_POST['Exercises']['SkillsIds'])
                $criteria->addNotInCondition('id', $_POST['Exercises']['SkillsIds']);
            if($group &&  Courses::existCourseById($group->id_course) && $group->Skills) {
                foreach($group->Skills as $groupSkill) {
                    if($_POST['Exercises']['SkillsIds'])
                    foreach($_POST['Exercises']['SkillsIds'] as $id_used) {
                        if($id_used == $groupSkill->id)
                            $dontadd = 1;
                    }
                    $res .= "<li data-dontadd='$dontadd' data-id='$groupSkill->id'><a href='#'>$groupSkill->name</a></li>";
                    unset($dontadd);
                }
                $res .= '<li class="divider"></li>';
                $criteria->addNotInCondition('id', $group->IdsUsedSkills);
            }
            
            $criteria->limit = 10;
            $skills = Skills::model()->findAll($criteria);
            
            if($skills) {
                foreach ($skills  as $skill)
                {
                    $res .= "<li data-dontadd data-id='$skill->id'><a href='#'>$skill->name</a></li>";
                }
            } else
                $res .= '<li data-dontadd="1" ><a href="#">Результатов нет</a></li>';
            
            echo $res;
	}
        
        public function actionCreateFromLesson($id_group)
	{
		$model=new Exercises;
                
                $exerciseGroup = GroupOfExercises::model()->findByPk($id_group);
                if( !($exerciseGroup && Courses::existCourseById($exerciseGroup->id_course)) )
                    die('Такой группы уроков не сущесвует');
                
		if(isset($_POST['Exercises']))
		{
			$model->attributes=$_POST['Exercises'];
                        $model->change_date = date('Y-m-d H:i:s');
                        if($model->save())
                        {
                            $exerciseAndGroup = new GroupAndExercises();
                            $exerciseAndGroup->id_group = $id_group;
                            $exerciseAndGroup->id_exercise = $model->id;
                            $exerciseAndGroup->order = GroupAndExercises::maxValueOrder($id_group);
                            if($exerciseAndGroup->save())
                                echo 1;
                        } 	
		}
	}
        
        public function actionCreateFromGroup($id_group)
	{
		$model=new Exercises;
                
                $exerciseGroup = GroupOfExercises::model()->findByPk($id_group);
                if( !($exerciseGroup && Courses::existCourseById($exerciseGroup->id_course)) )
                    die('Такой группы уроков не сущесвует');
		if(isset($_POST['Exercises']))
		{
			$model->attributes=$_POST['Exercises'];
                        $model->course_creator_id = $exerciseGroup->id_course;
                        $model->change_date = date('Y-m-d H:i:s');
                        if($model->save())
                        {
                            $exerciseAndGroup = new GroupAndExercises();
                            $exerciseAndGroup->id_group = $id_group;
                            $exerciseAndGroup->id_exercise = $model->id;
                            $exerciseAndGroup->order = GroupAndExercises::maxValueOrder($id_group);
                            if($exerciseAndGroup->save())
                                echo 1;
                        } 	
		}
	}
        
	public function actionSaveChange($id_group, $id_part=null)
	{
            $group = GroupOfExercises::model()->findByPk($id_group);
            if(!($_POST['Exercises'] && $group && Courses::existCourseById($group->id_course)))
                return false;

            foreach($_POST['Exercises'] as $id_exercise => $attributes)
            {
                $model= Exercises::model()->findByPk($id_exercise);
                if(!$model)
                    die('Не существует такого задания !');
                
                $model->attributes = $attributes;
                $model->change_date = date('Y-m-d H:i:s');
                if($model->canSaveFromGroup($id_group))
                {
                    if($model->save())
                    {
                        if($attributes['SkillsIds'])
                        {
                            // сначала удаляем все удаленные скиллы
                            $criteria = new CDbCriteria;
                            $criteria->condition = '`id_exercise` = :id_exercise';
                            $criteria->params['id_exercise'] = $id_exercise;
                            $criteria->addNotInCondition('id_skill', $attributes['SkillsIds']);
                            ExerciseAndSkills::model()->deleteAll($criteria);
                            $exerciseAndSkills = new ExerciseAndSkills();
                            // добавляем скиллы
                            foreach($attributes['SkillsIds'] as $skill_id)
                            {
                                if(!ExerciseAndSkills::model()->findByAttributes(array('id_exercise'=>$id_exercise, 'id_skill'=>$skill_id)))
                                {
                                    $exerciseAndSkills->id_exercise = $id_exercise;
                                    $exerciseAndSkills->id_skill = $skill_id;  
                                    $exerciseAndSkills->save();
                                    $exerciseAndSkills->id = false;
                                    $exerciseAndSkills->isNewRecord = true;
                                }
                            }
                        } else {
                            // если скиллы пусты значит удаляем все скиллы
                            ExerciseAndSkills::model()->deleteAllByAttributes(array('id_exercise'=>$id_exercise));
                        }
                        echo 1;
                    } else {
                        die('Введите текст задания');
                    }
                } else {
                    $newExercise = new Exercises();
                    $newExercise->attributes = $model->attributes;
                    $newExercise->course_creator_id = $group->id_course;
                    if($newExercise->save()) 
                    {
                        $userExerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_exercise_group'=>$id_group));
                        foreach($userExerciseGroups as $userExerciseGroup)
                        {
                            UserAndExercises::model()->deleteAllByAttributes(array('id_relation'=>$userExerciseGroup->id, 'id_exercise'=>$id_exercise));
                        }
                        if($attributes['SkillsIds'])
                        {
                            $exerciseAndSkills = new ExerciseAndSkills();
                            foreach($attributes['SkillsIds'] as $skill_id)
                            {
                                $exerciseAndSkills->id_exercise = $newExercise->id;
                                $exerciseAndSkills->id_skill = $skill_id;  
                                $exerciseAndSkills->save();
                                $exerciseAndSkills->id = false;
                                $exerciseAndSkills->isNewRecord = true;
                            }
                        }
                        if($group->type == 1)
                        {
                            $groupAndExercises = GroupAndExercises::model()->findByAttributes(array('id_group'=>$group->id, 'id_exercise'=>$model->id));
                            $groupAndExercises->id_exercise = $newExercise->id;
                            $groupAndExercises->id_group = $group->id;
                            $groupAndExercises->order = GroupAndExercises::maxValueOrder($id_group);
                            $groupAndExercises->save(false);
                        } elseif($group->type == 2 AND $id_part)
                        {
                            $partExercise = PartsOfTestAndExercises::model()->findByAttributes(array('id_part'=>$id_part, 'id_exercise'=>$model->id));
                            $partExercise->id_exercise = $newExercise->id;
                            $partExercise->save();
                        }
                        
                        $group->change_date = date('Y-m-d H:i:s');
                        $group->save(false);

                        echo 1;
                    } else {
                        die('Введите текст задания');
                    }

                }

            }
	}

	public function loadModel($id)
	{
		$model=Exercises::model()->findByPk($id);
		if($model===null or !$model->canChange)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exercises-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionSWFUpload($id_course, $id_part=null)
        {
            $id_course = (int) $id_course;
            if($id_course)
            {
                if(!Courses::existCourseById($id_course))
                {
                    echo "<script>alert('Импорт невыполнен, данный курс вам не принадлежит!');</script>";
                    return false;
                }
            }
            if ($_FILES['ImportFile'])
            {

                if ($file = CUploadedFile::getInstanceByName("ImportFile"))
                { 
                    $file = file($file->TempName);
                        //if (($handle = fopen($file->TempName, "r")) !== FALSE) 
                    $x = $y = 0;
                        foreach ($file as $f)
                        {
                            $y++;
                            //$data = explode(";", $f);
                            if (($data = str_getcsv($f, ";")) !== FALSE) 
                            {
                                $num = count($data);
                                if ($num != 5)
                                {
                                    
                                }
                                else
                                {
                                    $condition = iconv('cp1251', 'utf-8', $data[0]);
                                    $answer = iconv('cp1251', 'utf-8', $data[1]);
                                    $difficulty = iconv('cp1251', 'utf-8', $data[2]);
                                    $skills = iconv('cp1251', 'utf-8', $data[3]);
                                    $need_answer = iconv('cp1251', 'utf-8', $data[4]);
                                    
                                    if($condition && $answer && $difficulty && $skills)
                                    {
                                        $x++;
                                        
                                        $exercise = new Exercises;
                                        $exercise->condition = $condition;
                                        $exercise->correct_answers = $answer;
                                        $exercise->difficulty = $difficulty;
                                        $exercise->need_answer = $need_answer + 0;
                                        $exercise->course_creator_id = $id_course;
                                        $exercise->change_date = date('Y-m-d H:i:s');
                                        $exercise->id_type = 1;
                                        $exercise->id_visual = 1;
                                        $exercise->save();
                                        
                                        foreach(explode(';', $skills) as $s)
                                        {
                                            if(!($skill = Skills::model()->findByAttributes(array('name'=>$s))))
                                            {
                                                $skill = new Skill;
                                                $skill->name = $s;
                                                $skill->type = 2;
                                                $skill->id_course = $id_course;
                                                $skill->save();
                                            }
                                            if($skill->id)
                                            {
                                                $exerciseandskills = new ExerciseAndSkills;
                                                $exerciseandskills->id_exercise = $exercise->id;
                                                $exerciseandskills->id_skill = $skill->id;
                                                $exerciseandskills->save();
                                            }
                                        }
                                        if($id_part)
                                        {
                                            $partExercise = new PartsOfTestAndExercises;
                                            $partExercise->id_part = $id_part;
                                            $partExercise->id_exercise = $exercise->id;
                                            $partExercise->save();
                                        }
                                    }
                                }
                            }
                            fclose($handle);
                        }
                    
                    echo "<script>alert('".(($x==$y && $x > 0)?"Импорт успешно выполнен! ":"")."Импортировано $x записей из $y.');location.reload();</script>";
                }
            }
        }
}
