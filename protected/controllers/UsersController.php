<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    
	public $layout='//layouts/main';

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
				'actions'=>array('update'),
				'roles'=>array('student'),
			),
			array('allow',
				'actions'=>array('forget', 'accessDenyByConfirm', 'activate'),
				'users'=>array('?'),
			),
			array('allow',
				'actions'=>array('recovery', 'achievements', 'unsubscribe'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
	public function actionUpdate()
	{
            $model=$this->loadModel(Yii::app()->user->id);
            // вторая модель нужна для вывода username. Так как после присвоения
            // модели емейл присваивается, и выводится не сохраненный новый мейл
            $user=clone $model;
            if(isset($_POST['Users']))
            {
                 $model->attributes=$_POST['Users'];;
                 if($_POST['Users']['username'] or isset($_POST['Users']['send_notifications']))
                     $model->checkPassword = $model->password;
                 if($model->validate())
                 {
                     if($_POST['Users']['password'])
                         $model->password = md5(Yii::app()->params['beginSalt'].$model->password.Yii::app()->params['endSalt']);
                     if($model->sendOnMail)
                     {
//                        CMailer::send(
//                             array(
//                                 'email' => $model->email,
//                                 'name' => $model->email,
//                             ),
//                             array(
//                                 'email' => Yii::app()->params['adminEmail'],
//                                 'name' => Yii::app()->name
//                             ),
//                             Yii::app()->name,
//                             array(
//                                 'template' => 'change_password',
//                                 'vars' => array(
//                                     'site_name'=>Yii::app()->name,
//                                     'password'=> $_POST['Users']['password'],
//                                 ),
//                             )
//                        );
                     }

                     $model->save(false);

                     if($_POST['Users']['username'])
                     {
                        $auth = new UserIdentity($model->username, $model->password);
                        $auth->authenticate(true);
                        Yii::app()->user->login($auth, 0);
                     }
                     $this->redirect('/users/update');
                 }
            }
            
            
            if($_POST['RemoveParent'])
            {
                $relation = $model->ParentRelation;
                
                CMailer::send(
                     array(
                         'email' => $relation->Parent->email,
                         'name' => $relation->Parent->email,
                     ),
                     array(
                         'email' => Yii::app()->params['adminEmail'],
                         'name' => Yii::app()->name
                     ),
                     Yii::app()->name,
                     array(
                         'template' => 'break_family_ties',
                         'vars' => array(
                             'username_child'=>$model->username,
                         ),
                     )
                );
                
                $relation->status = 2;
                $relation->save(false);
            }
            
            $this->render('update',array(
                    'model'=>$model,
                    'user'=>$user,
            ));
	}

        public function actionActivate($key) {
            if($key) {
                $user = Users::model()->findByAttributes(array('confirm_key'=>$key));
                if($user) {
                    $user->confirm_key = NULL;
                    $user->save(false);
                    $auth = new UserIdentity($user->username, $user->password);
                    $auth->authenticate(true);
                    Yii::app()->user->login($auth, 0);
                    $this->redirect(array('courses/list'));
                }
            }
            $this->redirect('/');
        }
        
        public function actionAchievements($key=null)
        {
            if($key)
                $user = Users::model()->findByAttributes(array('progress_key'=>$key));
            else
                $user = Users::model()->findByAttributes(array('id'=>Yii::app()->user->id));
            if(!$user)
                $this->redirect('/');
            $this->render('achievements', array(
                'user'=>$user,
                'progress_key'=>$key,
            ));
        }
        
        public function actionRecovery($key) {
            if($key)
            { 
                $user = Users::model()->findByAttributes(array('password_recovery_key'=>$key));
                if($user)
                {
                    if($_POST['Users'])
                    {
                        $user->password = $_POST['Users']['password'];
                        $user->checkPassword = $_POST['Users']['checkPassword'];
                        if($user->validate())
                        {
                            $user->password = md5(Yii::app()->params['beginSalt'].$user->password.Yii::app()->params['endSalt']);
                            $user->password_recovery_key = NULL;
                            $user->save(false);
                            $auth = new UserIdentity($user->username, $user->password);
                            $auth->authenticate(true);
                            Yii::app()->user->login($auth, 0);
                            $this->redirect(Users::getLogoLink());
                        }
                    }
                    $user->password = null;
                    $this->render('recovery', array(
                        'user'=>$user,
                    ));
                    die;
                }
            }
            $this->redirect('/');
        }
        
        public function actionUnsubscribe($key) {
            if($key)
            { 
                $user = Users::model()->findByAttributes(array('unsubscribe_key'=>$key));
                if($user)
                {
                    $user->send_mailing = 0;
                    $user->unsubscribe_key = NULL;
                    $user->save(false);
                    $this->render('unsubscribe', array(
                        'user'=>$user,
                    ));
                    die;
                }
            }
            $this->redirect('/');
        }
        
        public function actionForget() {
            $model = new Users;
            $username = '';
            $errorUsername = '';
            $recoveryAnswer = '';
            $errorRecoveryAnswer = '';
            $error = '';
            $canRecovery = true;
            
            if($_POST)
            {
                $username = $_POST['username'];
                $recoveryAnswer = $_POST['recovery_answer'];
                
                
                if($username=='')
                {
                    $errorUsername = 'Введите ваш логин или email';
                    $canRecovery = false;
                }
                
                if($canRecovery)
                {
                    $user = Users::model()->findByAttributes(array('username'=>$username));
                    if(!$user)
                    {
                        $errorUsername = 'Пользователь с таким псевдонимом или email не зарегистрирован';
                        $canRecovery = false;
                    }
                }
                
                
                if($canRecovery && $user->role==2 && $recoveryAnswer=='')
                {
                    if(isset($_POST['recovery_answer']))
                    {
                        $errorRecoveryAnswer = 'Введите ответ для восстановления';
                    }
                    $canRecovery = false;
                }
                
                if($canRecovery && $user->role==2 && $user->recovery_answer!=$recoveryAnswer)
                {
                    $error = 'Логин или ответ на вопрос не верен. Проверьте правильность введенных вами данных.';
                    $canRecovery = false;
                }
                
                if($canRecovery)
                {
                    $user->password_recovery_key = md5('polux'.uniqid().$user->id.'sun');
                    $user->save(false);
                    if($user->role==2)
                    {
                        $this->redirect(array('users/recovery', 'key'=> $user->password_recovery_key));
                    }
                    else
                    {
                        $url = CHtml::link('восстановить пароль', array('users/recovery', 'key'=>$user->password_recovery_key));

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
                                'template' => 'password_recovery',
                                'vars' => array(
                                    'url'=>$url,
                                    'email' => $user->email,
                                    'site_name'=>Yii::app()->name,
                                ),
                            )
                        );

                        $this->render('successForget', array(
                            'user'=>$user,
                        ));
                        die;
                    }
                }
            }
            
            $this->render('forget', array(
                'model'=>$model,
                'user'=>$user,
                'errorUsername'=>$errorUsername,
                'username'=>$username,
                'recoveryAnswer'=>$recoveryAnswer,
                'errorRecoveryAnswer'=>$errorRecoveryAnswer,
                'error'=>$error,
            ));
        }
        
        public function actionAccessDenyByConfirm()
        {
            $this->render('accessDenyByConfirm');
        }
        
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
