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
                            'actions'=>array('delete','index', 'massdelete', 'update'),
                            'users'=>Users::Admins(),
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
		$user=$this->loadModel(Yii::app()->user->id);
		if(isset($_POST['Users']))
		{
                //print_r($_POST['Users']); die;
                     $model->attributes=$_POST['Users'];
                     if($_POST['Users']['email'])
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
                if($user->type != 1)
                    $user->delete();
	}

	public function actionMassDelete()
	{
            if($_POST['checked'])
            {
                foreach($_POST['checked'] as $id_user) 
                {
                    $user = Users::model()->findByPk($id_user);
                    if($user->type != 1)
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

		$this->render('index',array(
			'model'=>$model,
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
