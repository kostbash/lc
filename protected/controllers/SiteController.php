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
                $this->redirect(array('courses/index','id'=>Courses::$defaultCourse));
            
            $loginForm = new LoginForm;
            $user = new Users;
            
            $showRegModal = $_GET['showreg'];
            $showLoginModal = $_GET['showlogin'];
            
            // регистрация
            if(isset($_POST['Users']))
            {
                if($user->registration($_POST['Users']))
                {
                    $loginForm->username = $user->email;
                    $loginForm->password = $user->temporary_password;
                    $loginForm->rememberMe = $user->rememberMe;
                    $loginForm->login();
                    $this->redirect(array('courses/index','id'=>Courses::$defaultCourse));
                } else {
                    $showRegModal = true;
                }
            }
            
            // вход
            if(isset($_POST['LoginForm']))
            {
                    $loginForm->attributes=$_POST['LoginForm'];
                    if($loginForm->validate() && $loginForm->login()) {
                        $loggedUser = Users::model()->findByPk(Yii::app()->user->id);
                        if($loggedUser->role == 1)
                            $this->redirect(array('admin/courses/index'));
                        else
                            $this->redirect(array('courses/index','id'=>Courses::$defaultCourse));
                    } else
                        $showLoginModal = true;
            }
            
            $this->render('index', array(
                'loginForm' => $loginForm,
                'user' => $user,
                'showRegModal'=>$showRegModal,
                'showLoginModal'=>$showLoginModal,
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

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
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
        
        public function actionMoveAnswers()
        {
            $exercises = Exercises::model()->findAll();
            foreach($exercises as $exercise)
            {
            if($exercise->id_type==1)
            {
            $answer = new ExercisesListOfAnswers;
            $answer->id_exercise = $exercise->id;
            $answer->answer = $exercise->correct_answers;
            $answer->is_right = 1;
            $answer->save(false);
            } elseif($exercise->id_type==2)
            {
            $answer = ExercisesListOfAnswers::model()->findByPk((int)$exercise->correct_answers);
            if($answer)
            {
            $answer->is_right = 1;
            $answer->save(false);
            }
            } elseif($exercise->id_type==3)
            {
            $idsRightAnswer = explode(',', $exercise->correct_answers);
            if(is_array($idsRightAnswer))
            {
            foreach($idsRightAnswer as $id)
            {
            $answer = ExercisesListOfAnswers::model()->findByPk((int) $id);
            if($answer)
            {
            $answer->is_right = 1;
            $answer->save(false);
            }
            unset($answer);
            }
            }
            } else {
            echo 'тип контент';
            }
            if($answer)
            unset($answer);
            }
        }
}