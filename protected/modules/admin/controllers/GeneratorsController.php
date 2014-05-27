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
                            'actions'=>array('settings', 'generation'),
                            'users'=>Users::Admins(),
                    ),
                    array('deny',
                            'users'=>array('*'),
                    ),
            );
	}
        
        public function actionGeneration($id) {
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
                
                error_reporting(E_ERROR); // максимальная ошибка, которая может быть Parse error. В случае шаблона или условия без оператора между двумя пременными - '5 + 5 x1 x2'
                
                if($_POST['Exercises'])
                {
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
                    
                    $exerciseSkills = new ExerciseAndSkills;
                    foreach($_POST['Exercises'] as $attributes)
                    {
                        $exercise = new Exercises;
                        $exercise->attributes = $attributes;
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
                    $count=0; // количество успешных генераций
                    $attempts = 0; // попытки сгенировать
                    $exercises = array();
                    while(($count < $generator->Template->number_exercises) && $attempts < 1000)
                    {
                        $forReplace = $generator->Template->ForPeplace;
                        $convertedTemplate = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $generator->Template->template);
                        $convertedConditions = Generators::getConvertStrings($forReplace['patterns'], $forReplace['replacements'], $generator->Template->conditionsArray);
                        if(GeneratorsTemplates::ConditionsMet($convertedConditions))
                        {
                            $exerciseModel = new Exercises;
                            $exerciseModel->question = $convertedTemplate;
                            $exerciseModel->correct_answer = Generators::executeCode($convertedTemplate);
                            $exerciseModel->number = $count;
                            $exercises[$count] = $exerciseModel;
                            $count++;
                        }
                        $attempts++;
                    }
                }
                
		$this->render('generation',array(
			'generator'=>$generator,
                        'group'=>$group,
                        'exercises' => $exercises,
                        'attempts' => $attempts,
                        'count' => $count,
		));
        }

	public function actionSettings($id)
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
                
                $redirect = $part ? array('/admin/generators/generation', 'id'=>$id, 'id_part'=>$id_part) : array('/admin/generators/generation', 'id'=>$id, 'id_group'=>$id_group);
                
                if(!$group)
                    $this->redirect('/');
                
		if(isset($_POST['GeneratorsTemplates']))
		{
                    if($generator->Template)
                    {
                        $template = $generator->Template;
                    }
                    else
                    {
                        $template = new GeneratorsTemplates;
                        $template->id_user = Yii::app()->user->id;
                        $template->id_generator = $generator->id;
                    }
                    
                    $template->attributes = $_POST['GeneratorsTemplates'];
                    
                    if($template->save())
                    {
                        if($_POST['GeneratorsTemplatesVariables'])
                        {
                            GeneratorsTemplatesVariables::model()->deleteAllByAttributes(array('id_template'=>$template->id));
                            $newVar = new GeneratorsTemplatesVariables;
                            foreach($_POST['GeneratorsTemplatesVariables'] as $attributesVar)
                            {
                                if($attributesVar['value_max'] > $attributesVar['value_min'])
                                {
                                    $newVar->attributes = $attributesVar;
                                    $newVar->id_template = $template->id;
                                    $newVar->save();
                                    $newVar->isNewRecord = true;
                                    $newVar->id = false;
                                }
                            }
                        }
                        
                        if($_POST['GeneratorsTemplatesConditions'])
                        {
                            GeneratorsTemplatesConditions::model()->deleteAllByAttributes(array('id_template'=>$template->id));
                            $newCond = new GeneratorsTemplatesConditions;
                            foreach($_POST['GeneratorsTemplatesConditions'] as $attributesCond)
                            {
                                $newCond->attributes = $attributesCond;
                                $newCond->id_template = $template->id;
                                $newCond->save();
                                $newCond->isNewRecord = true;
                                $newCond->id = false;
                            }
                        }
                        $this->redirect($redirect);
                    }
		}
                
		$this->render('settings',array(
			'generator'=>$generator,
                        'group'=>$group,
		));
	}
        
	public function loadModel($id)
	{
		$model=Generators::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
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
