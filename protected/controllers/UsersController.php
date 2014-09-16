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
				'actions'=>array('activate', 'recovery', 'successreg', 'achievements'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('update'),
				'roles'=>array('student'),
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
                         $this->redirect('/users/update');
                     }
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
                    $auth = new UserIdentity($user->email, $user->password);
                    $auth->authenticate(true);
                    Yii::app()->user->login($auth, 0);
                    //$this->redirect(array('users/update'));
                    $this->redirect(array('courses/index', 'id'=>Courses::$defaultCourse));
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
        
//        public function actionRecovery($key) {
//            if($key) {
//                $user = Users::model()->findByAttributes(array('password_recovery_key'=>$key));
//                if($user) {
////                    $user->password_recovery_key = NULL;
////                    $user->save(false);
//                    $this->redirect('/users/recovery');
//                }
//                    
//            }
//            $this->redirect('/');
//        }
        
        public function actionSuccessReg() {
            $this->layout = '//layouts/begin';
            $this->render('success');
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
