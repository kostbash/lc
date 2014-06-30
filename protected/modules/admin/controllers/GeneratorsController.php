<?php

class GeneratorsController extends Controller
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
                    array('allow',
                            'actions'=>array('settings', 'generation', 'gethtmlvisual', 'dictionaries', 'adddictionary', 'editdictionary', 'deletedictionary'),
                            'users'=>Users::Admins(),
                    ),
                    array('deny',
                            'users'=>array('*'),
                    ),
            );
	}
        
        public function actionGeneration($id)
        {
            $generator=$this->loadModel($id);
            $id_group = (int) $_GET['id_group'];
            $id_part = (int) $_GET['id_part'];

            if($id_group)
            {
                $group = GroupOfExercises::model()->findByPk($id_group);
            }
            elseif($id_part)
            {
                $part = PartsOfTest::model()->findByPk($id_part);
                $group = $part->Group;
            }

            if(!$group)
                $this->redirect('/');

            if($_POST['Exercises'] && $_POST['Template'])
            {
//                CVarDumper::dump($_POST, 10, true);
//                die;
                if($group->type == 1) // если добавляем задания в блок
                {
                    $groupExercises = new GroupAndExercises;
                    $redirect = array('/admin/groupofexercises/update', 'id'=>$id_group);
                }
                elseif($group->type == 2) // если добавляем в часть теста
                {
                    if(!$part)
                    {
                        $part = new PartsOfTest;
                        $part->id_group = $id_group;
                        $part->order = PartsOfTest::maxValueOrder($id_group);
                        $part->save();
                    }
                    $partExercises = new PartsOfTestAndExercises;
                    $redirect = array('/admin/partsoftest/update', 'id'=>$part->id);
                }

                $exerciseSkills = new ExerciseAndSkills; // нужен для поиска, поэтому тут
                foreach($_POST['Exercises'] as $attributes)
                {
                    $exercise = new Exercises;
                    $exercise->attributes = $attributes;
                    $exercise->id_type = $_POST['Template']['id_type'];
                    $exercise->id_visual = $_POST['Template']['id_visual'];
                    $exercise->course_creator_id = $group->id_course;
                    if($exercise->save())
                    {
                        if($attributes['SkillsIds'])
                        {
                            foreach($attributes['SkillsIds'] as $skill_id)
                            {
                                $exerciseSkills->id_exercise = $exercise->id;
                                $exerciseSkills->id_skill = $skill_id;
                                $exerciseSkills->save();
                                $exerciseSkills->id = false;
                                $exerciseSkills->isNewRecord = true;
                            }
                        }
                        
                        if($attributes['answers'])
                        {
                            $idsExercises = array();
                            while($attributes['answers'])
                            {
                                $rand = array_rand($attributes['answers']);
                                $exercisesAnswers = new ExercisesListOfAnswers;
                                $exercisesAnswers->attributes = $attributes['answers'][$rand];
                                $exercisesAnswers ->id_exercise = $exercise->id;
                                if($exercisesAnswers->save())
                                {
                                    $idsExercises[$rand] = $exercisesAnswers->id;
                                }
                                unset($attributes['answers'][$rand]);
                            }
                        }
                        
                        if($attributes['comparisons'])
                        {
                            $comparison = new ExercisesComparisons;
                            foreach($attributes['comparisons'] as $attrs)
                            {
                                $comparison->answer_one = $idsExercises[$attrs['answer_one']];
                                $comparison->answer_two = $idsExercises[$attrs['answer_two']];
                                $comparison->id_exercise = $exercise->id;
                                $comparison->save();
                                $comparison->id = false;
                                $comparison->isNewRecord = true;
                            }
                        }

                        if($part) // если существует часть значит добавляем задания в нее
                        {
                            $partExercises->id_part = $part->id;
                            $partExercises->id_exercise = $exercise->id;
                            $partExercises->save();
                            $partExercises->id = false;
                            $partExercises->isNewRecord = true;
                        }
                        else // если части нет. Значит добавляем в группу
                        { 
                            $groupExercises->id_group = $id_group;
                            $groupExercises->id_exercise = $exercise->id;
                            $groupExercises->order = GroupAndExercises::maxValueOrder($id_group);
                            $groupExercises->save();
                            $groupExercises->id = false;
                            $groupExercises->isNewRecord = true;
                        }
                    }
                }
                $this->redirect($redirect);
            }
            else
            {
                $visual = ExercisesVisuals::model()->findByPk($generator->template->id_visual);
                $generatorFactory = new GeneratorsFactory($generator->id);
                $resultGeneration = $generatorFactory->generateExercises();
            }

            $this->render('generation',array(
                    'generator'=>$generator,
                    'group'=>$group,
                    'visual'=>$visual,
                    'exercises' => $resultGeneration['exercises'],
                    'attempts' => $resultGeneration['attempts'],
                    'count' => $resultGeneration['count'],
                    'answers' => $resultGeneration['answers'],
                    'comparisons' => $resultGeneration['comparisons'],
            ));
        }

	public function actionSettings($id)
	{
		$generator=$this->loadModel($id);
                $id_group = (int) $_GET['id_group'];
                $id_part = (int) $_GET['id_part'];
                
                $words = new GeneratorsWords('search');
                $words->unsetAttributes();
                $words->attributes = $_POST['Words'];
                
                if($id_group)
                {
                    $group = GroupOfExercises::model()->findByPk($id_group);
                }
                elseif($id_part)
                {
                    $part = PartsOfTest::model()->findByPk($id_part);
                    $group = $part->Group;
                }
                
                $redirect = isset($part) ? array('/admin/generators/generation', 'id'=>$id, 'id_part'=>$id_part) : array('/admin/generators/generation', 'id'=>$id, 'id_group'=>$id_group);
                
                if(!$group)
                    $this->redirect('/');
                
		if(isset($_POST['GeneratorsTemplates']))
		{
                    $generatorFactory = new GeneratorsFactory($generator->id, $_POST);
                    if($generatorFactory->saveSettings())
                    {
                        $this->redirect($redirect);
                    }
		}
                
		$this->render("settings_$generator->id",array(
			'generator'=>$generator,
                        'group'=>$group,
                        'words'=>$words,
		));
	}
        
        public function actionDictionaries($id_gen) {
            $generator = Generators::model()->findByPk($id_gen);
            if(!$generator)
                $this->redirect('/');
            
            $words = new GeneratorsWords('search');
            $words->unsetAttributes();
            $words->attributes = $_POST['Words'];
            if(isset($_POST['checked']))
            {
                if(!empty($_POST['checked']))
                {
                    $generator->addSelectedWords($_POST['checked']);
                }
                
                $this->redirect($_SESSION['returnUrl']);
            } else {
                $_SESSION['returnUrl'] = Yii::app()->request->urlReferrer;
            }
            
            $this->render("dictionaries", array(
                'generator'=>$generator,
                'words'=>$words,
            ));
        }
        
        public function actionAddDictionary($id_gen)
        {
            $name = $_POST['name'];
            $result = array();
            $model = new GeneratorsDictionaries;
            $model->name = $name;
            $model->id_generator = $id_gen;
            if($model->save())
            {
                $result['success'] = 1;
                $result['id'] = $model->id;
                $result['name'] = $model->name;
            } else {
                $result['success'] = 0;
            }
            echo CJSON::encode($result);
        }
        
        public function actionDeleteDictionary()
        {
            $result = array('success'=>0);
            $id_dict = (int) $_POST['id_dict'];
            $dict = GeneratorsDictionaries::model()->findByPk($id_dict);
            if($dict)
            {
                $dict->delete();
                $result['success'] = 1;
            }
            echo CJSON::encode($result);
        }
        
        public function actionEditDictionary()
        {
            $id = (int) $_POST['id'];
            $result = array('success'=>0);
            $model = GeneratorsDictionaries::model()->findByPk($id);
            $model->attributes = $_POST['attributes'];
            if($model->save())
            {
                $result['name'] = $model->name;
                $result['success'] = 1;
            }
            
            echo CJSON::encode($result);
        }
        
	public function loadModel($id)
	{
		$model=Generators::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        public function actionGetHtmlVisual() {
            $id_visual = (int) $_POST['id_visual'];
            $result = array();
            if($id_visual && ExercisesVisuals::model()->exists('id=:id', array('id'=>$id_visual)))
            {
                $result['success'] = 1;
                $result['html'] = $this->renderPartial("visualizations/{$id_visual}", array('model'=> new GeneratorsTemplates), true);
            } else {
                $result['success'] = 0;
            }
            echo CJSON::encode($result);
        }

	/**
	 * Performs the AJAX validation.
	 * @param Generators $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='generators-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
