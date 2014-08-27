<?php

class StudentsOfTeacherController extends Controller
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
                            'actions'=>array('confirmdeal', 'regectdeal'),
                            'users'=>array('*'),
                    ),
                    array('allow',
                            'actions'=>array('index', 'view', 'delete', 'create', 'update'),
                            'roles'=>array('teacher'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
        public function actionConfirmDeal($deal)
        {
            $model = StudentsOfTeacher::model()->findByAttributes(array('confirm'=>$deal));
            if($model)
            {
                $model->status = 1;
                $model->confirm = NULL;
                $model->regect = NULL;
                if($model->save(false))
                {
                    
                }
            }
            $this->redirect('/');
        }
        
        public function actionRegectDeal($deal)
        {
            $model = StudentsOfTeacher::model()->findByAttributes(array('regect'=>$deal));
            if($model)
            {
                $model->status = 2;
                $model->confirm = NULL;
                $model->regect = NULL;
                if($model->save(false))
                {
                    
                }
            }
            $this->redirect('/');
        }

//        Истользуеться для подтверждения с сайта, а не по эмейлу
//        public function actionConfirmDeal()
//        {
//            $result = array('success'=>0);
//            $id = (int) $_POST['id'];
//            $answer = (int) $_POST['answer'];
//            if($answer && $id)
//            {
//                $model = StudentsOfTeacher::model()->findByAttributes(array('id'=>$id, 'id_student'=>Yii::app()->user->id));
//                if($model)
//                {
//                    $model->status = $answer;
//                    if($model->save())
//                    {
//                        $result['success'] = 1;
//                    }
//                }
//            }
//            echo CJSON::encode($result);
//        }
        
	public function actionView($id)
	{
            $model = $this->loadConfirmedModel($id);
            
            $notifications = new StudentNotifications('search');
            $notifications->unsetAttributes();
            $notifications->id_user = $model->id_student;
            
            $exercisesLogs = new UserExercisesLogs('search');
            $exercisesLogs->unsetAttributes();
            $exercisesLogs->id_user = $model->id_student;
            
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
            
            $this->render('view',array(
                    'model'=>$model,
                    'notifications'=>$notifications,
                    'exercisesLogs'=>$exercisesLogs,
                    'notificationsDataProvider' => $notifications->search(),
                    'exercisesLogsDataProvider' => $exercisesLogs->search(),
            ));
	}

	public function actionCreate()
	{
            $model=new StudentsOfTeacher;
            
            if(isset($_POST['StudentsOfTeacher']) && isset($_POST['Users']['email']))
            {
                $user = Users::model()->findByAttributes(array('email'=>$_POST['Users']['email']));
                if(!$user)
                {
                    $user=new Users;
                    $_POST['Users']['role'] = 2;
                    $user->registration($_POST['Users']);
                }
                
                $existRecord = StudentsOfTeacher::model()->find("id_teacher=:id_teacher AND id_student=:id_student", array('id_teacher'=>Yii::app()->user->id, 'id_student'=>$user->id));
                
                $model->attributes=$_POST['StudentsOfTeacher'];
                $model->id_student=$user->id;
                $model->id_teacher=Yii::app()->user->id;
                
                if($model->validate())
                {
                    if($model->id_student != $model->id_teacher)
                    {
                        if(!$existRecord)
                        {
                            $model->confirm = substr(md5($user->email.$model->Teacher->email.uniqid().'podtverditika'), 0,26);
                            $model->regect =  substr(md5($model->Teacher->email.uniqid().$user->email.'otklonitika'), 0,26);
                            $model->save(false);
                            
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
                                    'template' => 'deal_from_teacher',
                                    'vars' => array(
                                        'email_teacher'=> $model->Teacher->email,
                                        'confirm_link' => CHtml::link('Подтвердить', array('/admin/studentsofteacher/confirmdeal', 'deal' => $model->confirm)),
                                        'regect_link' => CHtml::link('Отклонить', array('/admin/studentsofteacher/regectdeal', 'deal' => $model->regect)),
                                        'site_name'=>Yii::app()->name,
                                    ),
                                )
                            );
                            
                            $this->redirect(array('index'));
                        } else
                        {
                            $user->addError('email', "У вас уже есть ученик ($existRecord->student_name $existRecord->student_surname) c таким адресом электронной почты.");
                        }
                    } else
                    {
                        $user->addError('email', 'Вы не можете добавить сами себя в ученики');
                    }
                }
            } else {
                $user=new Users;
            }
            
            $this->render('create',array(
                    'model'=>$model,
                    'user'=>$user,
            ));
	}

	public function actionUpdate($id)
	{
            $model=$this->loadModel($id);
            $cloneModel = clone $model; // сохраняем модель до присвоения атрибутов

            if(isset($_POST['StudentsOfTeacher']))
            {
                    $model->attributes=$_POST['StudentsOfTeacher'];
                    if($model->save())
                            $this->redirect(array('index'));
            }

            $this->render('update',array(
                    'model'=>$model,
                    'cloneModel'=>$cloneModel,
            ));
	}

	public function actionDelete($id)
	{
            $this->loadModel($id)->delete();
	}

	public function actionIndex()
	{
		$model=new StudentsOfTeacher('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StudentsOfTeacher']))
			$model->attributes=$_GET['StudentsOfTeacher'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=StudentsOfTeacher::model()->findByAttributes(array('id'=>$id, 'id_teacher'=>Yii::app()->user->id));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
	public function loadConfirmedModel($id)
	{
		$model=StudentsOfTeacher::model()->findByAttributes(array('id'=>$id, 'id_teacher'=>Yii::app()->user->id, 'status'=>1));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='students-of-teacher-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
