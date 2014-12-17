<?php

class MailWorkpiecesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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

	public function accessRules()
	{
            return array(
                    array('allow', // allow admin user to perform 'admin' and 'delete' actions
                            'actions'=>array('sended','unsended', 'updatebyajax', 'sendmessage'),
                            'roles'=>array('admin'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
	}
        
	public function actionSended()
	{
            $model=new MailWorkpieces('search');
            $model->unsetAttributes();
            $model->send = 1;
            
            if(isset($_GET['MailWorkpieces']))
                    $model->attributes=$_GET['MailWorkpieces'];

            $this->render('sended',array(
                    'model'=>$model,
            ));
	}
        
	public function actionUnSended()
	{
            $model=new MailWorkpieces('search');
            $model->unsetAttributes();
            
            $model->send = 0;
            if(isset($_GET['MailWorkpieces']))
                    $model->attributes=$_GET['MailWorkpieces'];

            $this->render('unsended',array(
                    'model'=>$model,
            ));
	}
        
	public function actionUpdateByAjax()
	{
            $result = array('success'=>0);
            if($_POST['MailWorkpieces'] && is_array($_POST['MailWorkpieces']))
            {
                foreach($_POST['MailWorkpieces'] as $id => $mailWorkpiece)
                {
                    $model=MailWorkpieces::model()->findByPk($id);
                    if($model)
                    {
                        $model->subject = $mailWorkpiece['subject'];
                        $model->template = $mailWorkpiece['template'];
                        if($model->save())
                        {
                            $result['success'] = 1;
                        }
                        else
                        {
                            $result['errors'] = print_r($model->errors, true);
                        }
                    }
                }
            }
            echo CJSON::encode($result);
	}
        
        public function actionSendMessage($id)
        {
            $result = array('result'=>0);
            $model=MailWorkpieces::model()->findByPk($id);
            if($model && !$model->send)
            {
                if($model->subject && $model->template)
                {
                    $addressee = $model->getAddressee(true);
                    if($addressee)
                    {
                        
                        if(!$addressee->unsubscribe_key)
                        {
                            $addressee->unsubscribe_key = md5(Yii::app()->params['vazgen'].$addressee->username.uniqid().Yii::app()->params['zahodi']);
                            $addressee->save(false);
                        }
                        
                        $send = CMailer::send(
                            array(
                                'email' => $addressee->email,
                                'name' => $addressee->email,
                            ),
                            array(
                                'email' => Yii::app()->params['adminEmail'],
                                'name' => 'Cursys.ru',
                            ),
                            $model->subject."Чтобы отписаться от рассылки нажмите на ссылку - ".CHtml::link('Отписаться', array('users/unsubscribe', 'key'=>$addressee->unsubscribe_key)),
                            array(
                                'text' => $model->template,
                                'vars' => array(
                                    'site_name'=>Yii::app()->name,
                                ),
                            )
                        );

                        if($send)
                        {
                            $result['success'] = 1;
                            $result['message'] = 'Сообщение отправлено !';
                            
                            $model->send = 1;
                            $model->save(false);
                        }
                        else
                        {
                            $result['error'] = 'Сообщение не было отправлено';
                        }
                    }
                    else
                    {
                        $result['error'] = 'Нет адресата';
                    }
                }
                else
                {
                    $result['error'] = 'Тема письма и сообщение должны быть заполнены';
                }
            }
            else
            {
                $result['error'] = 'Заготовка уже была отправлена';
            }

            echo CJSON::encode($result);
        }
        
	public function loadModel($id)
	{
		$model=MailWorkpieces::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mail-workpieces-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
