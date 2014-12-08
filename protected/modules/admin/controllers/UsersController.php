<?php

class UsersController extends Controller
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
                            'actions'=>array('update'),
                            'roles'=>array('student'),
                    ),
                    array('allow',
                            'actions'=>array('delete','index', 'massdelete', 'resetpassword', 'logs'),
                            'roles'=>array('admin'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
	public function actionCreate()
	{
		$model=new Users;

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate()
	{
		$model=$this->loadModel(Yii::app()->user->id);
                // вторая модель нужна для вывода username. Так как после присвоения
                // модели емейл присваивается, и выводится не сохраненный новый мейл
		$user=clone $model;
		if(isset($_POST['Users']))
		{
                     $model->attributes=$_POST['Users'];
                     if($_POST['Users']['email'] or isset($_POST['Users']['send_notifications']))
                         $model->checkPassword = $model->password;
                     if($model->validate())
                     {
                         if($_POST['Users']['password'])
                             $model->password = md5(Yii::app()->params['beginSalt'].$model->password.Yii::app()->params['endSalt']);
                         if($model->sendOnMail)
                         {
                             CMailer::send(
                                 array(
                                     'email' => $model->email,
                                     'name' => $model->email,
                                 ),
                                 array(
                                     'email' => Yii::app()->params['adminEmail'],
                                     'name' => Yii::app()->name
                                 ),
                                 Yii::app()->name,
                                 array(
                                     'template' => 'change_password',
                                     'vars' => array(
                                         'site_name'=>Yii::app()->name,
                                         'password'=> $_POST['Users']['password'],
                                     ),
                                 )
                             );
                         }
                         
                         $model->save(false);
                         
                         if($_POST['Users']['email'])
                         {
                            $auth = new UserIdentity($model->email, $model->password);
                            $auth->authenticate(true);
                            Yii::app()->user->login($auth, 0);
                         }
                         $this->redirect('/admin/users/update');
                     }
		}

		$this->render('update',array(
			'model'=>$model,
                        'user'=>$user,
		));
	}

	public function actionDelete($id)
	{
		$user = $this->loadModel($id);
                // не даем удалять других админов
                if($user->role != 1)
                    $user->delete();
	}

	public function actionResetPassword($id)
	{
		$user = $this->loadModel($id);
                // не даем сбрасывать пароль других админов
                $result = array('success'=>0);
                if($user->role != 1)
                {
                    $user->temporary_password = substr(md5('lol'.uniqid().'azaza'), 0, 10);
                    $user->password = $user->temporary_password;
                    $user->checkPassword = $user->temporary_password;
                    if($user->validate())
                    {
                        $user->password = md5(Yii::app()->params['beginSalt'].$user->password.Yii::app()->params['endSalt']);
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
                                'template' => 'reset_password',
                                'vars' => array(
                                    'site_name'=>Yii::app()->name,
                                    'new_password'=>$user->temporary_password,
                                ),
                            )
                        );
                        $result['success'] = 1;
                    }
                }
                echo CJSON::encode($result);
	}

	public function actionMassDelete()
	{
            if($_POST['checked'])
            {
                foreach($_POST['checked'] as $id_user) 
                {
                    $user = Users::model()->findByPk($id_user);
                    // не даем удалять других админов
                    if($user->role != 1)
                        $user->delete();
                }
            }
            $this->redirect(array('/admin/users/index'));
	}

	public function actionIndex()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];
                
                if(!isset($_GET['Users']['registration_day']))
                {
                    $model->registration_day = key(Users::listRegistrationDates());
                }

		$this->render('index',array(
			'model'=>$model,
		));
	}
        
        public function actionLogs($id)
        {
            $user = Users::model()->findByAttributes(array( 'id'=>$id, 'role'=>array(2,3,4) ));
            if(!$user)
            {
                $this->redirect(array('/admin/users/index'));
            }

            $notifications = new StudentNotifications('search');
            $notifications->unsetAttributes();
            $notifications->id_user = $user->id;
            $notifications->lookAdmin = true;
            
            $exercisesLogs = new UserExercisesLogs('search');
            $exercisesLogs->unsetAttributes();
            $exercisesLogs->id_user = $user->id;
            $exercisesLogs->lookAdmin = true;
            
            if($_POST['Notifications'])
            {
                $notifications->attributes = $_POST['Notifications'];
                
                unset($_SESSION['Notifications']);
                $_SESSION['Notifications'] = $_POST['Notifications'];
                $_GET['filter'] = 1;
            } elseif($_GET['filter'])
            {
                $notifications->attributes = $_SESSION['Notifications'];
            }
            
            if($_POST['ExercisesLogs'])
            {
                $exercisesLogs->attributes = $_POST['ExercisesLogs'];
                
                unset($_SESSION['ExercisesLogs']);
                $_SESSION['ExercisesLogs'] = $_POST['ExercisesLogs'];
                $_GET['filter'] = 1;
            } elseif($_GET['filter'])
            {
                $exercisesLogs->attributes = $_SESSION['ExercisesLogs'];
            }
            
            $this->render('logs',array(
                'user'=>$user,
                'notifications'=>$notifications,
                'exercisesLogs'=>$exercisesLogs,
                'notificationsDataProvider' => $notifications->search(),
                'exercisesLogsDataProvider' => $exercisesLogs->search(),
            ));
        }

	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
