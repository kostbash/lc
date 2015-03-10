<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex()
	{
            if(!Yii::app()->user->isGuest)
                $this->redirect(array('courses/list'));
            
            $loginForm = new LoginForm;
            $user = new Users;
            
            $tab = '';
            $regLoginType = $_POST['regLoginType'];
            
            $role = (int) $_GET['role'];
            if($role)
            {
                if($role==2 or $role==4)
                {
                    $user->role = $role;
                }
            }
            
            // регистрация
            if(isset($_POST['Users']))
            {
                if($user->registration($_POST['Users']))
                {
                    $_SESSION['goals'][] = 'Register';
                    if($user->role==2)
                    {
                        $loginForm->username = $user->username;
                        $loginForm->password = $user->temporary_password;
                        $loginForm->rememberMe = $user->rememberMe;
                        $loginForm->login();

                        $id_course = (int) $_SESSION['id_course'];

                        $link = array('courses/list');

                        if($id_course)
                        {
                            unset($_SESSION['id_course']);
                            if(Courses::model()->exists('id=:id_course', array('id_course'=>$id_course)))
                            {
                                $link = array('courses/view', 'id'=>$id_course);
                            }
                        }
                        
                        $_SESSION['goals'][] = 'RegisterStudent';
                        
                        $this->render('look_pass', array(
                            'user'=>$user,
                            'link'=>$link,
                        ));
                        die;
                    }
                    elseif($user->role==4)
                    {
                        $_SESSION['goals'][] = 'RegisterParent';
                        $this->render('successReg', array(
                            'user'=>$user,
                        ));
                        die;
                    }
                } else {
                    $tab = 'reg';
                }
            }
            
            // вход
            if(isset($_POST['LoginForm']))
            {
                    $loginForm->attributes=$_POST['LoginForm'];
                    if($loginForm->validate() && $loginForm->login()) {
                        $loggedUser = Users::model()->findByPk(Yii::app()->user->id);
                        $id_course = (int) $_SESSION['id_course'];
                        if($loggedUser->role == 2)
                        {
                            $_SESSION['goals'][] = 'LoginStudent';
                            $_SESSION['checkNewParent'] = true;
                            $_SESSION['checkNewTeacher'] = true;
                        }
                        if($id_course)
                            $this->redirect(array('courses/view', 'id'=>$id_course));
                        else
                            $this->redirect(array('courses/list'));
                    } else
                        $tab = 'login';
            }
            
            $subjects = CourseSubjects::model()->findAll(array('order'=>'`order` ASC'));

            $_SESSION['goals'][] = 'HomeGuest';
            
            $this->render('index', array(
                'loginForm' => $loginForm,
                'user' => $user,
                'subjects' => $subjects,
                'regLoginType'=>$regLoginType,
                'tab'=>$tab,
            ));
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
                        else
                            $this->render('error', $error);	
		}
	}

	public function actionContact()
	{
            $model=new ContactForm;
            if(isset($_POST['ContactForm']))
            {
                    $model->attributes=$_POST['ContactForm'];
                    if($model->validate())
                    {
                            $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                            $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
                            $headers="From: $name <{$model->email}>\r\n".
                                    "Reply-To: {$model->email}\r\n".
                                    "MIME-Version: 1.0\r\n".
                                    "Content-Type: text/plain; charset=UTF-8";

                            mail(Yii::app()->params['adminEmail'].", kartofanchik98@gmail.com",$subject,$model->body,$headers);
                            Yii::app()->user->setFlash('contact','Спасибо за обращение. Мы ответим вам в ближайшее время.');
                            $this->refresh();
                    }
            }
            $this->render('contact',array('model'=>$model));
	}

	public function actionLogout()
	{
            Yii::app()->user->logout();
            $this->redirect(Yii::app()->homeUrl);
	}
        
	public function actionLogin()
	{
            $this->layout='//layouts/empty_simple';
            $model=new LoginForm;

            if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            if(isset($_POST['LoginForm']))
            {
                $model->attributes=$_POST['LoginForm'];
                if($model->validate() && $model->login())
                        $this->redirect(Yii::app()->user->returnUrl);
            }
            
            $this->render('login', array('model'=>$model));
	}
        
//        public function actionMoveAnswers()
//        {
//            $exercises = Exercises::model()->findAll();
//            foreach($exercises as $exercise)
//            {
//            if($exercise->id_type==1)
//            {
//                $answer = new ExercisesListOfAnswers;
//                $answer->id_exercise = $exercise->id;
//                $answer->answer = $exercise->correct_answers;
//                $answer->is_right = 1;
//                $answer->save(false);
//            } elseif($exercise->id_type==2)
//            {
//                $answer = ExercisesListOfAnswers::model()->findByPk((int)$exercise->correct_answers);
//                if($answer)
//                {
//                    $answer->is_right = 1;
//                    $answer->save(false);
//                }
//            } elseif($exercise->id_type==3)
//            {
//            $idsRightAnswer = explode(',', $exercise->correct_answers);
//            if(is_array($idsRightAnswer))
//            {
//                foreach($idsRightAnswer as $id)
//                {
//                $answer = ExercisesListOfAnswers::model()->findByPk((int) $id);
//                if($answer)
//                {
//                $answer->is_right = 1;
//                $answer->save(false);
//                }
//                unset($answer);
//                }
//                }
//            } else {
//                echo 'тип контент';
//            }
//            if($answer)
//            unset($answer);
//            }
//        }
}