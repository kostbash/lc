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
            
            $this->layout = '//layouts/begin';
            $loginForm = new LoginForm;
            $user = new Users;
            
            $showRegModal = $_GET['showreg'];
            $showLoginModal = $_GET['showlogin'];
            
            // регистрация
            if(isset($_POST['Users']))
            {
                $user->attributes = $_POST['Users'];
                $temporary_password = substr(md5('lol'.uniqid().'azaza'), 0, 10);
                $user->password = $temporary_password;
                $user->checkPassword = $temporary_password;
                $user->type = 2;
                if($user->validate())
                {
                    $user->password = md5(Yii::app()->params['beginSalt'].$user->password.Yii::app()->params['endSalt']);
                    $user->progress_key = substr(md5(Yii::app()->params['beginSalt'].$user->email.Yii::app()->params['endSalt']), 0, 25);
                    //$user->confirm_key = md5(Yii::app()->params['beginSalt'].uniqid().Yii::app()->params['endSalt']);
                    $user->registration_day = date('Y-m-d');
                    $user->save(false);

                    CMailer::send(
                        array(
                            'email' => $user->email,
                            'name' => $user->email,
                        ),
                        array(
                            'email' => 'registration@cursys.ru',
                            'name' => 'Cursys.ru'
                        ),
                        Yii::app()->name,
                        array(
                            'template' => 'member_register',
                            'vars' => array(
                                'activate_link' => CHtml::link('Подтвердите профиль и перейдите к курсу', array('users/activate', 'key' => $user->confirm_key)),
                                'temporary_password' => $temporary_password,
                                'site_name'=>Yii::app()->name,
                            ),
                        )
                    );
                    
                    $loginForm->username = $user->email;
                    $loginForm->password = $temporary_password;
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
                        if($loggedUser->type == 2)
                            $this->redirect(array('courses/mylist'));
                        if($loggedUser->type == 1)
                            $this->redirect(array('admin/courses/index'));
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
}