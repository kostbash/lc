<?php

class LessonsController extends Controller
{
    
	public $layout='//layouts/column2';

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
				'actions'=>array('pass', 'nextgroup', 'saverightanswers'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        public function actionSaveRightAnswers($user_lesson, $group) {
            $userAndExerciseGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_exercise_group'=>$group, 'id_user_and_lesson'=>$user_lesson));
            if($userAndExerciseGroup && $_POST['Exercises']) 
            {
                $countRight = 0;
                foreach($_POST['Exercises'] as $id_exercise => $attr)
                    if(Exercises::isRightAnswer($id_exercise, $attr['answers']))
                        ++$countRight;
                    
                $userAndExerciseGroup->number_right = $countRight;
                $userAndExerciseGroup->passed = 1;
                $userAndExerciseGroup->number_all = count($_POST['Exercises']);
                $userAndExerciseGroup->save(false);
            }
        }

        public function actionPass($id, $group=null)
        {
            $userAndLesson = UserAndLessons::model()->findByAttributes(array('id'=>$id, 'id_user'=>Yii::app()->user->id));
            if(!$userAndLesson)
                $this->redirect('/');
            
            if($group)
                $userAndExerciseGroup = UserAndExerciseGroups::model()->findByAttributes(array('id_exercise_group'=>$group, 'id_user_and_lesson'=>$id));
            else {
                $userAndExerciseGroup = UserAndExerciseGroups::model()->find("`id_user_and_lesson`=$id AND id_user_and_lesson=$userAndLesson->id ORDER BY `id` DESC");
                // если не сущесвует связи
                if(!$userAndExerciseGroup && $userAndExerciseGroup->Group)
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
                if($_POST['Exercises'] && $_SESSION['exercisesTest']) 
                {
                    $countRight = 0;
                    $numberAllSkills = array();
                    $numberRightAnswerSkills = array();
                    
                    foreach($_SESSION['exercisesTest'] as $key => $id_exercise)
                    {
                        $rightAnswer = Exercises::isRightAnswer($id_exercise, $_POST['Exercises'][$key]['answers']);
                        if($rightAnswer)
                            ++$countRight;
                        foreach($userAndLesson->Lesson->Skills as $skill)
                        {
                            $exerciseHasSkill = ExerciseAndSkills::model()->exists('id_exercise=:id_exercise AND id_skill=:id_skill', array('id_exercise'=>$id_exercise,'id_skill'=>$skill->id));
                           
                            if($exerciseHasSkill)
                            {
                                // общее кол-во заданий имеющий данный скилл урока
                                $numberAllSkills[$skill->id] += 1;
                                // кол-во правильных ответов заданий по скиллам урока
                                if($rightAnswer)
                                    $numberRightAnswerSkills[$skill->id] += $rightAnswer;
                            }
                        }
                    }
                    $userAndExerciseGroup->number_right = $countRight;
                    $userAndExerciseGroup->number_all = count($_SESSION['exercisesTest']);
                    
                    // если урок еще не пройденный, то делаем проверку
                    $testPassed = 1;
                    $resultTest = array();
                    foreach($numberAllSkills as $id_skill => $numberSkill)
                    {
                        $skill = Skills::model()->findByPk($id_skill);
                        $resultTest[$id_skill]['achieved'] = round(($numberRightAnswerSkills[$id_skill]/$numberSkill)*100, 0, PHP_ROUND_HALF_DOWN);
                        $resultTest[$id_skill]['need']= Lessons::PercentNeedBySkill($userAndLesson->id_lesson, $id_skill);
                        $resultTest[$id_skill]['skill'] = $skill;
                        if($resultTest[$id_skill]['need'] > $resultTest[$id_skill]['achieved']) {
                            $testPassed = 0;
                        }
                    }

                    if(!$userAndExerciseGroup->passed)
                        $userAndExerciseGroup->passed = $testPassed;
                    $userAndExerciseGroup->save(false);
                    foreach($numberRightAnswerSkills as $skill_id => $numberRight)
                    {
                        $userGroupSkills = UserExerciseGroupSkills::model()->findByAttributes(array('id_user_and_lesson'=>$userAndLesson->id, 'id_test_group'=>$userAndExerciseGroup->id_exercise_group, 'id_skill'=>$skill_id));
                        if(!$userGroupSkills)
                        {
                            $userGroupSkills = new UserExerciseGroupSkills;
                            $userGroupSkills->id_user_and_lesson = $userAndLesson->id;
                            $userGroupSkills->id_test_group = $userAndExerciseGroup->id_exercise_group;
                            $userGroupSkills->id_skill = $skill_id;
                            $userGroupSkills->number_all = $numberAllSkills[$skill_id];
                            $userGroupSkills->right_answers = $numberRight;
                            
                        } else {
                            if($numberAllSkills[$skill_id] && $userGroupSkills->number_all && $numberRight/$numberAllSkills[$skill_id] > $userGroupSkills->right_answers/$userGroupSkills->number_all)
                            {
                                $userGroupSkills->number_all = $numberAllSkills[$skill_id];
                                $userGroupSkills->right_answers = $numberRightAnswerSkills[$skill_id];
                            }
                        }
                        $userGroupSkills->save(false);
                    }
                    
                    // сохраняем достижения пользователя
                    $user = Users::model()->findByAttributes(array('id'=>Yii::app()->user->id));
                    $user->experience = $user->CountPassTest;
                    $user->accuracy = $user->AveragePoint;
                    $user->wisdom = $user->hasSkills;
                    $user->save(false);
                    
                    $this->render('successtest',array(
                        'userLesson'=>$userAndLesson,
                        'userAndExerciseGroup'=>$userAndExerciseGroup,
                        'exerciseGroup'=>$currentExerciseGroup,
                        'testPassed' => $testPassed,
                        'resultTest' => $resultTest,
                    ));
                    $render = false;
                }
                
                $exercisesTest = $currentExerciseGroup->ExercisesTest;
                // т.к. задания выбираются постоянно новые, мы сохраняем этот набор, чтобы его потом проверить
                unset($_SESSION['exercisesTest']);
                foreach($exercisesTest as $keyMass => $exerciseTest)
                {
                    $_SESSION['exercisesTest'][$keyMass] = $exerciseTest->id;
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

            if(!$render)
                die;
            $this->render('pass',array(
                    'userLesson'=>$userAndLesson,
                    'userAndExerciseGroup'=>$userAndExerciseGroup,
                    'exerciseGroup'=>$currentExerciseGroup,
                    'exercisesTest' => $exercisesTest,
            ));
        }
        
        public function actionCheck($step=1)
        {
            $this->layout='//layouts/begin';
            $course = Courses::model()->findByPk(Courses::$defaultCourse);
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
                    $answersLog->answer = $exercise['answer'];
                    $answersLog->save();
                }
                if($nextGroup)
                    $this->redirect(array('lessons/check', 'step'=>$step+1));
                else
                {
                    $userAnswers = AnswersLog::model()->findAllByAttributes(array('ip'=>$_SERVER['REMOTE_ADDR']));
                    $rightAnswers = 0;
                    foreach($userAnswers as $userAnswer)
                    {
                        if(Exercises::isRightAnswer($userAnswer->id_exercise, $userAnswer->answer))
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
}
