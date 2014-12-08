<?php

class ChildrenController extends Controller
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
                    'accessControl', 
                    'postOnly + delete',
            );
	}

	public function accessRules()
	{
            return array(
                    array('allow',
                            'actions'=>array('confirmdeal', 'regectdeal'),
                            'users'=>array('*'),
                    ),
                
                    array('allow',
                            'actions'=>array('confirmDealFromSite'),
                            'roles'=>array('student'),
                    ),
                
                    array('allow',
                            'actions'=>array('index', 'view', 'delete', 'create', 'update'),
                            'roles'=>array('parent'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
        public function actionConfirmDeal($deal)
        {
            $model = Children::model()->findByAttributes(array('confirm'=>$deal));
            if($model)
            {
                $model->status = 1;
                $model->confirm = NULL;
                $model->regect = NULL;
                if($model->save(false))
                {
                    // отклоняем все другие предложения, так как теперь у нас есть родитель
                    $childrenParents = Children::model()->findAllByAttributes(array('id_child'=>$model->id_child, 'status'=>0));
                    foreach($childrenParents as $childrenParent)
                    {
                        $childrenParent->status=2;
                        $childrenParent->save();
                    }
                }
            }
            $this->redirect('/');
        }
        
        public function actionRegectDeal($deal)
        {
            $model = Children::model()->findByAttributes(array('regect'=>$deal));
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

        //Используеться для подтверждения с сайта, а не по эмейлу
        public function actionConfirmDealFromSite()
        {
            $result = array('success'=>0);
            $id = (int) $_POST['id'];
            $answer = (int) $_POST['answer'];
            if($answer && $id)
            {
                $model = Children::model()->findByAttributes(array('id'=>$id, 'id_child'=>Yii::app()->user->id));
                if($model)
                {
                    $model->status = $answer;
                    if($model->save())
                    {
                        if($model->status==1)
                        {
                            // отклоняем все другие предложения, так как теперь у нас есть родитель
                            $childrenParents = Children::model()->findAllByAttributes(array('id_child'=>Yii::app()->user->id, 'status'=>0));
                            foreach($childrenParents as $childrenParent)
                            {
                                $childrenParent->status=2;
                                $childrenParent->save();
                            }
                        }
                        $result['success'] = 1;
                    }
                }
            }
            echo CJSON::encode($result);
        }
        
	public function actionView($id)
	{
            $model = $this->loadConfirmedModel($id);
            
            $notifications = new StudentNotifications('search');
            $notifications->unsetAttributes();
            $notifications->id_user = $model->id_child;
            
            $exercisesLogs = new UserExercisesLogs('search');
            $exercisesLogs->unsetAttributes();
            $exercisesLogs->id_user = $model->id_child;
            
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
            $model=new Children;
            $user=new Users;
            if(isset($_POST['Children']) && isset($_POST['Users']['username']))
            {
                $user = Users::model()->findByAttributes(array('username'=>$_POST['Users']['username']));
//                if(!$user)
//                {
//                    $user=new Users;
//                    $_POST['Users']['role'] = 2;
//                    $user->registration($_POST['Users']);
//                }
                
                if($user)
                {
                    $exist = Children::model()->exists("id_child=:id_child AND id_parent=:id_parent", array('id_child'=>$user->id, 'id_parent'=>Yii::app()->user->id));
                    if(!$exist)
                    {
                        $existParent = Children::model()->exists("id_child=:id_child AND status=:status", array('id_child'=>$user->id, 'status'=>1));

                        $model->attributes=$_POST['Children'];
                        $model->id_child=$user->id;
                        $model->id_parent=Yii::app()->user->id;

                        if($model->validate())
                        {
                            if($model->id_child != $model->id_parent)
                            {
                                if(!$existParent)
                                {
                                    $model->confirm = substr(md5($user->username.$model->Parent->email.uniqid().'podtverditiko'), 0,26);
                                    $model->regect =  substr(md5($model->Parent->email.uniqid().$user->email.'otklonitiko'), 0,26);
                                    $model->save(false);

        //                            CMailer::send(
        //                                array(
        //                                    'email' => $user->email,
        //                                    'name' => $user->email,
        //                                ),
        //                                array(
        //                                    'email' => 'registration@cursys.ru',
        //                                    'name' => 'Cursys.ru'
        //                                ),
        //                                Yii::app()->name,
        //                                array(
        //                                    'template' => 'deal_from_parent',
        //                                    'vars' => array(
        //                                        'email_parent'=> $model->Parent->email,
        //                                        'confirm_link' => CHtml::link('Подтвердить', array('/admin/children/confirmdeal', 'deal' => $model->confirm)),
        //                                        'regect_link' => CHtml::link('Отклонить', array('/admin/children/regectdeal', 'deal' => $model->regect)),
        //                                        'site_name'=>Yii::app()->name,
        //                                    ),
        //                                )
        //                            );
                                    $this->redirect(array('index'));
                                } else
                                {
                                    $user->addError('username', 'Система допускает подключение к аккаунту ученика только одного родителя. У данного ученика уже подключен аккаунт родителя.
        Если вы хотите поменять подключенный аккаунт родителя, нужно сначала отключить предыдущий.');
                                }
                            }
                            else
                            {
                                $user->addError('username', 'Вы не можете быть сам себе ребеноком');
                            }
                        }
                    }
                    else
                    {
                        $user->addError('username', 'У вас уже есть этот ребенок');
                    }
                }
                else
                {
                    $user=new Users;
                    $user->addError('username', 'Ребенок с таким псевдонимом не зарегистрирован');
                }
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

            if(isset($_POST['Children']))
            {
                    $model->attributes=$_POST['Children'];
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
            $model=new Children('search');
            $model->unsetAttributes();
            if(isset($_GET['Children']))
                    $model->attributes=$_GET['Children'];

            $this->render('index',array(
                    'model'=>$model,
            ));
	}

	public function loadModel($id)
	{
            $model=Children::model()->findByAttributes(array('id'=>$id, 'id_parent'=>Yii::app()->user->id));
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
	}
        
	public function loadConfirmedModel($id)
	{
            $model=Children::model()->findByAttributes(array('id'=>$id, 'id_parent'=>Yii::app()->user->id, 'status'=>1));
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
	}

	protected function performAjaxValidation($model)
	{
            if(isset($_POST['ajax']) && $_POST['ajax']==='children-of-parent-form')
            {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }
	}
        
}
