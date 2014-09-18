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
				'actions'=>array('list','index', 'nextlesson'),
				'roles'=>array('student'),
			),
			array('allow',
				'actions'=>array('view'),
                                'users'=>array('?'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
        
        public function actionView($id){
            $course = $this->loadModel($id);
            $themesLessons = $course->themesLessons;
            $this->render('view',array(
                'course'=>$course,
                'themesLessons'=>$themesLessons,
            ));
        }
        
        public function actionAdd($id) {
            $course = $this->loadModel($id);
            $user = Users::model()->findByPk(Yii::app()->user->id);
            if($user) {
                if(!$course->hasUserCourse) {
                    $courseAndUsers = new CoursesAndUsers();
                    $courseAndUsers->id_course = $course->id;
                    $courseAndUsers->id_user = $user->id;
                    $courseAndUsers->save();
                }
                $this->redirect(array('/courses/index', 'id'=>$course->id));
            }
            
        }

	public function actionIndex($id, $lesson=null)
	{
            if(!$id)
                $id = Courses::$defaultCourse;
		$course = $this->loadModel($id);
                $user = Users::model()->findByPk(Yii::app()->user->id);
                
                $courseUser = CoursesAndUsers::model()->findByAttributes(array('id_course'=>$course->id, 'id_user'=>$user->id));
                // сущесвует ли курс у пользователя
                if($courseUser)
                {
                    if($courseUser->status == 1)
                    {
                        $courseUser->activity_date = date('Y-m-d H:i:s');
                        $courseUser->save();
                    }
                }
                else
                {
                    $courseUser = new CoursesAndUsers;
                    $courseUser->id_course = $course->id;
                    $courseUser->id_user = $user->id;
                    $courseUser->activity_date = date('Y-m-d H:i:s');
                    $courseUser->status = 1;
                    $courseUser->save();
                }
                
                $index = $user->role==1 ? 0 : 1;
                    
                if($lesson)
                    $userAndLesson = UserAndLessons::model()->findByPk($lesson);
                else {
                    $userAndLesson = UserAndLessons::model()->find("`id_course` = $id AND `id_user` = $user->id ORDER BY `id` DESC");
                    // если не сущесвует связи
                    if(!$userAndLesson)
                    {
                        if($course->LessonsGroups[0]->LessonsRaw[$index])
                        {
                            $userAndLesson = new UserAndLessons;
                            $userAndLesson->id_course = $id;
                            $userAndLesson->id_user = $user->id;
                            $userAndLesson->id_group = $course->LessonsGroups[0]->id;
                            $userAndLesson->id_lesson = $course->LessonsGroups[0]->LessonsRaw[$index]->id;
                            $userAndLesson->save(false);
                        }
                    }
                }
                
		$this->render('index',array(
			'course'=>$course,
                        'userLesson'=>$userAndLesson,
                        'currentLesson'=>$userAndLesson->Lesson,
                        'pos'=>$index,
		));
	}
        
        public function actionNextLesson($id_user_lesson) {
            $userLesson = UserAndLessons::model()->findByAttributes(array('id_user'=>Yii::app()->user->id, 'id'=>$id_user_lesson));
            if($userLesson)
            {
                $nextLesson = $userLesson->Course->nextLesson($userLesson->id_group, $userLesson->id_lesson);
                if(!$nextLesson)
                    $this->redirect(array('courses/index', 'id'=>$userLesson->id_course, 'lesson'=>$userLesson->id));
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
            $this->render('list',array(
                    'model'=>$model,
                    'lastActiveCourse' => $user->lastActiveCourse,
                    'activeCourses' => $user->lastCourses(1),
                    'passedCourses' => $user->lastCourses(2),
                    'subjects' => $subjects,
            ));
	}
        
	public function actionMyList()
	{
                $this->redirect(array('courses/index', 'id'=>Courses::$defaultCourse));
		$model=new Courses('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Courses']))
			$model->attributes=$_GET['Courses'];

		$this->render('mylist',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Courses::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
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
