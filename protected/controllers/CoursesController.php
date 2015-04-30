<?php

class CoursesController extends Controller
{
	public $layout='//layouts/main';
        
        
	public function filters()
	{
		return array(
			'accessControl', 
			'postOnly + delete',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('skillByAjax','list','index', 'nextlesson', 'print', 'toPdf', 'congratulation', 'unavailable'),
				'roles'=>array('student'),
			),
			array('allow',
				'actions'=>array('guestView'),
                                'users'=>array('?'),
			),
                        array('allow',
				'actions'=>array('view', 'test'),
                                'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
        
        public function actionView($id, $lesson=null)
        {
            if(Yii::app()->user->isGuest)
                $this->actionGuestView ($id);
            else
                $this->actionIndex ($id, $lesson);
        }
        
        public function actionGuestView($id){
            $course = $this->loadModel($id);
            $_SESSION['id_course'] = $id;
            $this->_course = $id;
            $this->render('view',array(
                'course'=>$course,
                'skills'=>$course->Skills,
            ));
        }
        
        public function actionAdd($id) {
            $this->_course = $id;
            $course = $this->loadModel($id);
            $user = Users::model()->findByPk(Yii::app()->user->id);
            if($user) {
                if(!$course->hasUserCourse) {
                    $courseAndUsers = new CoursesAndUsers();
                    $courseAndUsers->id_course = $course->id;
                    $courseAndUsers->id_user = $user->id;
                    $courseAndUsers->save();
                }
                $this->redirect(array('/courses/view', 'id'=>$course->id));
            }
        }
        
        public function actionCongratulation($id)
        {
            $this->_course = $id;
            $course = $this->loadModel($id);
            $courseUser = CoursesAndUsers::model()->findByAttributes(array('id_course'=>$course->id, 'id_user'=>Yii::app()->user->id, 'status'=>1));
            if($course->canComplete() && $courseUser)
            {
                $courseUser->passed_date = date('Y-m-d H:i:s');
                $courseUser->status = 2;
                $courseUser->save();
                
                $courseLog = new UserCoursesLogs;
                $courseLog->id_user = Yii::app()->user->id;
                $courseLog->id_course = $course->id;
                $courseLog->date = date('Y-m-d');
                $courseLog->time = date('H:i:s');
                $courseLog->duration = $course->userDuration;
                $courseLog->save();
            
                $this->render('congratulation', array(
                    'course'=>$course,
                ));
            }
            else
            {
                $this->redirect(array('/courses/view', 'id'=>$course->id));
            }
        }
        
        public function actionUnavailable($id)
        {
            $course = Courses::model()->findByPk($id);
            if($course && !$course->haveAccess)
            {
                $courseUser = CoursesAndUsers::model()->findByAttributes(array('id_course'=>$course->id, 'id_user'=>Yii::app()->user->id));
                if($courseUser)
                {
                    $this->render('unavailable', array(
                        'course'=>$course,
                    ));
                    die;
                }
            }

            $this->redirect(array('courses/list'));
        }

	public function actionIndex($id, $lesson=null)
	{
            $this->_course = $id;
            $course = $this->loadModel($id);
            $course->openForAdmin(); //открываем полность курс для админа
            $user = Users::model()->findByPk(Yii::app()->user->id);
            $courseUser = CoursesAndUsers::model()->findByAttributes(array('id_course'=>$course->id, 'id_user'=>$user->id));

            // сущесвует ли курс у пользователя
            if($courseUser)
            {
                if($courseUser->status == 1)
                {
                    $courseUser->activity_date = date('Y-m-d H:i:s');
                }
                $courseUser->save();
            }
            else
            {
                $courseUser = new CoursesAndUsers;
                $courseUser->id_course = $course->id;
                $courseUser->id_user = $user->id;
                $courseUser->activity_date = date('Y-m-d H:i:s');
                $courseUser->last_activity_date = date('Y-m-d H:i:s');
                $courseUser->status = 1;
                $courseUser->is_begin = 0;
                $courseUser->save();
            }
            
            if($lesson)
            {
                $userAndLesson = UserAndLessons::model()->findByPk($lesson);
            } 
            else
            {
                $userAndLesson = UserAndLessons::model()->find("`id_course` = $id AND `id_user` = $user->id ORDER BY `id` DESC");
                // если не сущесвует связи
                if(!$userAndLesson)
                {
                    if($nearestAvailableLesson = $course->nearestAvailableLesson)
                    {
                        $userAndLesson = new UserAndLessons;
                        $userAndLesson->id_course = $id;
                        $userAndLesson->id_user = $user->id;
                        $userAndLesson->id_group = $nearestAvailableLesson->Groups[0]->id;
                        $userAndLesson->id_lesson = $nearestAvailableLesson->id;
                        $userAndLesson->last_activity_date = date('Y-m-d H:i:s');
                        $userAndLesson->save(false);
                    }
                }
            }
            
            $courseUser->OnChangeCourse();
            
            if($_SESSION['checkNewParent'])
            {
                $newParents = Children::newParents();
                if($newParents)
                {
                    $newParent = $newParents[0]; // берем первое предложение
                }
                unset($_SESSION['checkNewParent']);
            }

            $nodes = null;
            $edges = null;
            foreach($course->Skills as $skill) {

                $nodes .= "{ data: { id: '$skill->id', name: '$skill->name', width: ".mb_strlen($skill->name, 'utf-8')*10 ." } },";
                foreach ($skill->TopSkills as $top) {
                    $edges .= "{ data: { source: '$skill->id', target: '$top->id' } },";
                }


            }

            $course->title = str_replace('{name}', $course->name, $course->title);

            $this->render('index',array(
                    'course'=>$course,
                    'userLesson'=>$userAndLesson,
                    'courseUser'=>$courseUser,
                    'currentLesson'=>$userAndLesson->Lesson,
                    'newParent' => $newParent,
                    'nodes'=>$nodes,
                    'edges'=>$edges,
                    'skills'=>$course->Skills,
            ));
	}
        
        public function actionToPdf($id, $with_right=false)
        {
            $course = $this->loadModel($id);
            $user = Users::model()->findByPk(Yii::app()->user->id);
            
            if($with_right && !Yii::app()->user->checkAccess('editor'))
            {
                $with_right = false;
            }
            
            $html = $this->renderPartial('export', array(
                    'course'=>$course,
                    'pos'=>$user->role==1 ? 0 : 1,
                    'pdf'=>true,
                    'with_right'=>$with_right,
                    ), true, true);
            
            $physicName = md5(uniqid().$user->id.'course-export').".pdf";
            $path = Yii::app()->params['pdfPath'].'/'.$physicName;
            $this->createPDF('css/export.css', $html, $path);
            
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment; filename=" . "'Курс_$course->name.pdf'");
            header("Content-Transfer-Encoding: binary ");  
            flush();
            readfile($path);
            unlink($path);
        }
        
        public function actionPrint($id, $with_right=false)
        {
            $this->layout = '//layouts/empty';
            $course = $this->loadModel($id);
            $user = Users::model()->findByPk(Yii::app()->user->id);
            Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/export.css");
            
            if($with_right && !Yii::app()->user->checkAccess('editor'))
            {
                $with_right = false;
            }
            
            $this->render('export',array(
                'course'=>$course,
                'pos'=>$user->role==1 ? 0 : 1,
                'with_right'=>$with_right,
            ));
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
        
        public function actionNextLesson($id_user_lesson) {
            $userLesson = UserAndLessons::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id'=>$id_user_lesson));
            if($userLesson)
            {
                $nextLesson = $userLesson->Course->nextLesson($userLesson->id_group, $userLesson->id_lesson);
                if(!$nextLesson)
                    $this->redirect(array('courses/view', 'id'=>$userLesson->id_course, 'lesson'=>$userLesson->id));
                $nextUserLesson = UserAndLessons::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id_course'=>$userLesson->id_course, 'id_group'=>$nextLesson['id_group'], 'id_lesson'=>$nextLesson['id_lesson']));
                if($nextUserLesson)
                    $this->redirect(array('lessons/pass', 'id'=>$nextUserLesson->id));
            }
            $this->redirect('/courses/mylist');
        }

	public function actionList()
	{
            $model=new Courses('search');
            $model->unsetAttributes();
            if(isset($_GET['Courses']))
                    $model->attributes=$_GET['Courses'];
            $user = Users::model()->findByPk(Yii::app()->user->id);
            $subjects = CourseSubjects::model()->findAll(array('order'=>'`order` ASC'));
            if($_SESSION['checkNewParent'])
            {
                $newParents = Children::newParents();
                if($newParents)
                {
                    $newParent = $newParents[0]; // берем первое предложение
                }
                unset($_SESSION['checkNewParent']);
            }

        if($_SESSION['checkNewTeacher'])
        {
            $newTeachers = StudentsOfTeacher::newTeachers();
            if($newTeachers)
            {
                $newTeacher = $newTeachers[0]; // берем первое предложение
            }
            unset($_SESSION['checkNewTeacher']);
        }

            $this->render('list',array(
                    'model'=>$model,
                    'lastActiveCourse' => $user->lastActiveCourse,
                    'activeCourses'    => $user->lastCourses(1),
                    'passedCourses'    => $user->lastCourses(2),
                    'subjects'         => $subjects,
                    'newParent'        => $newParent,
                    'newTeacher'       => $newTeacher,
            ));
	}
        
	public function actionMyList()
	{
                $this->redirect(array('courses/view', 'id'=>Courses::$defaultCourse));
		$model=new Courses('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Courses']))
			$model->attributes=$_GET['Courses'];

		$this->render('mylist',array(
			'model'=>$model,
		));
	}

    public function actionSkillByAjax($id) {
        $skill = Skills::model()->findByPk($id);
        echo $skill->condition;
    }

    public function actionTest() {
        $string = 'if isBlockPassed then
 inc(BlockIndex)
endif

switch(BlockIndex) {
case 1:
SetBlockType(btExersice)
SetBlockTitle(Посчитай до 100)
AddControlledU(19)
SetULevel(19, 0.6)
AddControlledU(66)
SetULevel(66, 0)
 addTask(6513)
 addTask(6514)
 addTask(18286)
 addTask(18287)
 addTask(18288)
 addTask(18289)
 addTask(18290)
 addTask(18291)
 addTask(18292)
 addTask(18293)
 addTask(18294)
 addTask(18295)
 addTask(18296)
 addTask(18297)
 addTask(18298)
 addTask(18299)
 addTask(18300)
 addTask(18301)
 addTask(18302)
 addTask(18303)
 addTask(18304)
 addTask(18305)
 addTask(18306)
break;
}
        ';

echo '<pre>';

        $u = 103;
        $result = 0;
        $block_type = 'btExercise';

        $skill_exercises = ExerciseAndSkills::model()->findAllByAttributes(array('id_skill'=>$u));
        $sql = 'SELECT * FROM oed_user_exercises_logs WHERE id_user = '.Yii::app()->user->id . ' and (';

        foreach($skill_exercises as $key => $skill_and_exercise) {
            if ($block_type != 'all') {
                $group_and_ex = GroupAndExercises::model()->findByAttributes(array('id_exercise'=>$skill_and_exercise->id_exercise));
                $block = GroupOfExercises::model()->findByPk($group_and_ex->id_group);
                if ($block) {
                    if ($block_type == 'btExercise' and $block->type != 1) {
                        continue;
                    }
                    if ($block_type == 'btTest' and $block->type != 2) {
                        continue;
                    }
                }

            }


            $sql .= 'id_exercise = ' . $skill_and_exercise->id_exercise;
            $count = $key;
            if (isset($skill_exercises[++$count])) {
                $sql .= ' or ';
            }
        }

        $sql .= ') and `right` = ' . $result;
        if ($period != 0) {
            $sql .= ' and TO_DAYS(NOW()) - TO_DAYS(date) <= '.$period;
        }


        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if (!$result) {
            return -1;
        }
        $date = $result[count($result)-1]['date'] . ' ' . $result[count($result)-1]['time'];
        $P = 7 * (count($result) + 1);
        if (time() - strtotime($date) < $P) {
            return 0;
        }

        return 10/(count($result)+1);
    }

	public function loadModel($id)
	{
		$model=Courses::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
                elseif(!$model->haveAccess)
                {
                    $this->redirect(array('courses/unavailable', 'id'=>$model->id));
                }
                
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='courses-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
