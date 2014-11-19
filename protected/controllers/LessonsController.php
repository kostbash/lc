<?php

class LessonsController extends Controller
{
    
	public $layout='//layouts/main';

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
                            'actions'=>array('check'),
                            'users'=>array('?'),
                    ),
                    array('allow',
                            'actions'=>array('pass', 'nextgroup', 'saverightanswers', 'printBlock', 'blockToPdf', 'printLesson', 'lessonToPdf'),
                            'roles'=>array('student'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
        public function actionSaveRightAnswers($user_lesson, $group) {
            $userAndExerciseGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_exercise_group'=>$group, 'id_user_and_lesson'=>$user_lesson));
            $result = array('success'=>0);
            if($userAndExerciseGroup) 
            {
                $countRight = 0;
                if($_POST['Exercises'])
                {
                    foreach($_POST['Exercises'] as $id_exercise => $attr)
                    {
                        if(Exercises::isRightAnswer($id_exercise, $attr['answers']))
                            ++$countRight;
                    }
                }
                    
                $userAndExerciseGroup->number_right = $countRight;
                $userAndExerciseGroup->passed = 1;
                $userAndExerciseGroup->number_all = count($_POST['Exercises']);
                if($userAndExerciseGroup->save())
                {
                    $result['success'] = 1;
                }
                
            }
            echo CJSON::encode($result);
        }

        public function actionPass($id, $group=null)
        {   
            
            $userAndLesson = UserAndLessons::model()->findByAttributes(array('id'=>$id, 'id_user'=>Yii::app()->user->id));
            if(!$userAndLesson)
                $this->redirect('/');
            
            if($group)
            {
                $userAndExerciseGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_exercise_group'=>$group, 'id_user_and_lesson'=>$id));
            }
            else
            {
                $userAndExerciseGroup = UserAndExerciseGroups::model()->find("`id_user_and_lesson`=:id AND id_user_and_lesson=:user_lesson ORDER BY `id` DESC", array('id'=>$id, 'user_lesson'=>$userAndLesson->id));
                // если не сущесвует связи
                if(!$userAndExerciseGroup)
                {
                    if($userAndLesson->Lesson->ExercisesGroups[0])
                    {
                        $userAndExerciseGroup = new UserAndExerciseGroups;
                        $userAndExerciseGroup->id_user_and_lesson = $userAndLesson->id;
                        $userAndExerciseGroup->id_exercise_group = $userAndLesson->Lesson->ExercisesGroups[0]->id;
                        $userAndExerciseGroup->save(false);
                    }
                }
            }
            
            $render = true;
            
            if($userAndExerciseGroup)
            {
                $currentExerciseGroup = GroupOfExercises::model()->findByPk($userAndExerciseGroup->id_exercise_group);
                
                if(isset($_POST['Exercises'])) 
                {
                    $_POST['Exercises'] = (array) $_POST['Exercises'];
                    if($currentExerciseGroup->type==1)
                    {
                        $userAndExerciseGroup->saveResultBlock($_POST['Exercises']);
                    }
                    elseif($currentExerciseGroup->type==2)
                    {
                        $resultTest = $userAndExerciseGroup->saveResultTest($_POST['Exercises'], $_SESSION['exercisesTest']);

                        $this->render('successtest',array(
                            'userLesson'=>$userAndLesson,
                            'userAndExerciseGroup'=>$userAndExerciseGroup,
                            'exerciseGroup'=>$currentExerciseGroup,
                            'resultTest' => $resultTest,
                        ));
                        $render = false;
                    }
                    
                }
                else
                {
                    if($currentExerciseGroup->type==2)
                    {
                        $exercisesTest = $currentExerciseGroup->ExercisesTest;
                        // т.к. задания выбираются постоянно новые для теста, мы сохраняем этот набор, чтобы его потом проверить
                        $_SESSION['exercisesTest'] = array();
                        foreach($exercisesTest as $keyMass => $exerciseTest)
                        {
                            $_SESSION['exercisesTest'][$keyMass] = $exerciseTest->id;
                        }
                    }
                }
            }
            
            $nextLesson = $userAndLesson->Course->nextLesson($userAndLesson->id_group, $userAndLesson->id_lesson);
            if($nextLesson)
            {
                $nextUserLesson = UserAndLessons::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$userAndLesson->id_course, 'id_group'=>$nextLesson['id_group'], 'id_lesson'=>$nextLesson['id_lesson']));
                if(!$nextUserLesson && $userAndLesson->Lesson->accessNextLesson($userAndLesson->id))
                {
                    $nextUserLesson = new UserAndLessons;
                    $nextUserLesson->id_user = $userAndLesson->id_user;
                    $nextUserLesson->id_course = $userAndLesson->id_course;
                    $nextUserLesson->id_group = $nextLesson['id_group'];
                    $nextUserLesson->id_lesson = $nextLesson['id_lesson'];
                    $nextUserLesson->passed = 0;
                    $nextUserLesson->save(false);
                }
            }

            if(isset($_POST['Exercises'])) 
            {
                if($currentExerciseGroup->type==1)
                {
                    $this->redirect($userAndExerciseGroup->nextLink);
                }
                elseif($currentExerciseGroup->type==2)
                {
                    die; // не рендерим дальше
                }
            }
            
            $this->_lesson = $userAndLesson->Lesson->id;
            $this->_block = $group;
            $this->render('pass',array(
                    'userLesson'=>$userAndLesson,
                    'userAndExerciseGroup'=>$userAndExerciseGroup,
                    'exerciseGroup'=>$currentExerciseGroup,
                    'exercisesTest' => $exercisesTest,
            ));
        }
        
        public function actionCheck($course, $step=1)
        {
            $course = Courses::model()->findByPk($course);
            $checkLesson = $course->LessonsGroups[0]->LessonsRaw[0];
            $currentGroup = $checkLesson->ExercisesGroups[$step-1];
            $nextGroup = $checkLesson->ExercisesGroups[$step] ? 1 : 0;
            if($currentGroup->type==1)
                $exercises = $currentGroup->Exercises;
            elseif($currentGroup->type==2)
                $exercises = $currentGroup->ExercisesTest;
            if(!($currentGroup or $exercises))
                $this->redirect('/');
            $leftStep = count($checkLesson->ExercisesGroups) - $step;
            $number = 0;
            $numberAll = 0;
            foreach($checkLesson->ExercisesGroups as $key => $exerciseGroup)
            {
                if($exerciseGroup->type==1)
                    $numberExercises = count($exerciseGroup->Exercises);
                elseif($exerciseGroup->type==2)
                    $numberExercises = count($exerciseGroup->ExercisesTest);
                
                if($key != $step-1)
                {
                    $number += $numberExercises;
                } elseif(!$nextGroup) {
                    $numberAll = $number+$numberExercises;
                } else {
                    break;
                }
            }

            if(isset($_POST['Exercises']))
            {
                AnswersLog::model()->deleteAllByAttributes(array(
                    'ip'=>$_SERVER['REMOTE_ADDR'],
                    'id_course' => $course->id,
                    'id_lesson_group' => $course->LessonsGroups[0]->id,
                    'id_lesson' => $checkLesson->id,
                    'id_exercise_group' => $currentGroup->id,
                ));

                foreach($_POST['Exercises'] as $id_exercise => $exercise)
                {
                    $answersLog = new AnswersLog;
                    $answersLog->ip = $_SERVER['REMOTE_ADDR'];
                    $answersLog->id_course = $course->id;
                    $answersLog->id_lesson_group = $course->LessonsGroups[0]->id;
                    $answersLog->id_lesson = $checkLesson->id;
                    $answersLog->id_exercise_group = $currentGroup->id;
                    $answersLog->id_exercise = $id_exercise;
                    $answersLog->date = date('Y-m-d');
                    $answersLog->answer = serialize($exercise['answers']);
                    $answersLog->save();
                }
                if($nextGroup)
                    $this->redirect(array('lessons/check', 'course'=>$course->id, 'step'=>$step+1));
                else
                {
                    $userAnswers = AnswersLog::model()->findAllByAttributes(array('ip'=>$_SERVER['REMOTE_ADDR']));
                    $rightAnswers = 0;
                    foreach($userAnswers as $userAnswer)
                    {
                        if(Exercises::isRightAnswer($userAnswer->id_exercise, unserialize($userAnswer->answer)))
                            $rightAnswers++;
                    }
                    
                    $result = Lessons::ResultCheck($rightAnswers, $numberAll);
                    
                    $this->render('successcheck',array(
                            'rightAnswers'=>$rightAnswers,
                            'numberAll'=>$numberAll,
                            'result'=>$result,
                    ));
                    die;
                }
            }
            
            $this->render('check',array(
                'course'=>$course,
                'checkLesson'=>$checkLesson,
                'exercises'=>$exercises,
                'nextGroup'=>$nextGroup,
                'number'=>$number,
                'leftStep'=>$leftStep,
            ));
        }
        
        public function actionNextGroup($id) {
            $userAndExerciseGroup = UserAndExerciseGroups::model()->findByPk($id);
            if(!($userAndExerciseGroup && $userAndExerciseGroup->passed && $userAndExerciseGroup->nextGroup))
                $this->redirect('/');
            $userAndLesson = UserAndLessons::model()->findByAttributes(array('id'=>$userAndExerciseGroup->id_user_and_lesson, 'id_user'=>Yii::app()->user->id));
            $exerciseGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_user_and_lesson'=>$userAndExerciseGroup->id_user_and_lesson, 'id_exercise_group'=>$userAndExerciseGroup->nextGroup->id_group_exercises));
            if(!$exerciseGroup)
            {
                $exerciseGroup = new UserAndExerciseGroups;
                $exerciseGroup->id_user_and_lesson = $userAndExerciseGroup->id_user_and_lesson;
                $exerciseGroup->id_exercise_group = $userAndExerciseGroup->nextGroup->id_group_exercises;
                $exerciseGroup->save(false);
            }
            $this->redirect(array('/lessons/pass', 'id'=>$userAndLesson->id, 'group'=>$exerciseGroup->id_exercise_group));
        }
                
	public function loadModel($id)
	{
		$model=Lessons::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lessons-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionBlockToPdf($block, $with_right=false)
        {
            $this->layout = '//layouts/empty';
            if($with_right && !Yii::app()->user->checkAccess('editor'))
            {
                $with_right = false;
            }
            
            $block = GroupOfExercises::model()->findByPk($block);
            if(!$block)
                $this->redirect('/');
            
            $exercises = $block->type==1 ? $block->Exercises : $block->ExercisesTest;
            
            if($with_right && !Yii::app()->user->checkAccess('editor'))
            {
                $with_right = false;
            }
            
            $html = $this->renderPartial('export_block', array(
                    'block'=>$block,
                    'exercises' => $exercises,
                    'with_right'=>$with_right,
                    'pdf'=>true,
                    ), true, true);
            
            $physicName = md5(uniqid().'export-block').".pdf";
            $path = Yii::app()->params['pdfPath'].'/'.$physicName;
            $this->createPDF('css/export.css', $html, $path);
            
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment; filename=" . "'Блок_$block->name.pdf'");
            header("Content-Transfer-Encoding: binary ");  
            flush();
            readfile($path);
            unlink($path);
        }
        
        public function actionPrintBlock($block, $with_right=false)
        {
            $this->layout = '//layouts/empty';

            if($with_right && !Yii::app()->user->checkAccess('editor'))
            {
                $with_right = false;
            }
            
            $block = GroupOfExercises::model()->findByPk($block);
            if(!$block)
                $this->redirect('/');
            
            
            $exercises = $block->type==1 ? $block->Exercises : $block->ExercisesTest;
            
            Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/export.css");
            
            $this->render('export_block',array(
                'block'=>$block,
                'exercises' => $exercises,
                'with_right'=>$with_right,
            ));
        }
        
        public function actionPrintLesson($id, $with_right=false)
        {
            $this->layout = '//layouts/empty';
            $userAndLesson = UserAndLessons::model()->findByAttributes(array('id'=>$id, 'id_user'=>Yii::app()->user->id));
            if(!$userAndLesson)
                $this->redirect('/');
            Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/export.css");
            
            if($with_right && !Yii::app()->user->checkAccess('editor'))
            {
                $with_right = false;
            }
            
            $this->render('export_lesson',array(
                'lesson'=>$userAndLesson->Lesson,
                'with_right'=>$with_right,
            ));
        }
        
        public function actionLessonToPdf($id, $with_right=false)
        {
            $userAndLesson = UserAndLessons::model()->findByAttributes(array('id'=>$id, 'id_user'=>Yii::app()->user->id));
            if(!$userAndLesson)
                $this->redirect('/');
            
            $lesson = $userAndLesson->Lesson;
            
            if($with_right && !Yii::app()->user->checkAccess('editor'))
            {
                $with_right = false;
            }
            
            $html = $this->renderPartial('export_lesson', array(
                        'lesson'=>$lesson,
                        'pdf'=>true,
                        'with_right'=>$with_right,
                    ), true, true);
            
            $physicName = md5(uniqid().'lesson-export').".pdf";
            $path = Yii::app()->params['pdfPath'].'/'.$physicName;
            $this->createPDF('css/export.css', $html, $path);
            
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment; filename=" . '"Урок_'.$lesson->name.'.pdf"');
            header("Content-Transfer-Encoding: binary ");  
            flush();
            readfile($path);
            unlink($path);
        }
        
        // сделать pdf из страницы html+php
        public function createPDF($css, $html, $fileName, $output = 'F')
        {
            include_once("protected/extensions/MPDF/mpdf.php");
            $mpdf = new mPDF('', 'A4', '8', 'Arial', 10, 10, 10, 10, 10, 10); //* задаем формат, отступы и.т.д. /
            $mpdf->charset_in = 'utf-8'; //* не забываем про русский /
            $style = file_get_contents($css); //* подключаем css/

            $mpdf->WriteHTML($style, 1);

            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html, 2); //* формируем pdf /
            $x=$mpdf->x;
            $y=$mpdf->y;
            $mpdf->Output($fileName, $output);
        }
}
