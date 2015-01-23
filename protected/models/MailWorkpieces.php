<?php

/**
 * This is the model class for table "oed_mail_workpieces".
 *
 * The followings are the available columns in table 'oed_mail_workpieces':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_parent
 * @property integer $id_rule
 * @property integer $number
 * @property string $subject
 * @property string $template
 * @property string $form_date
 * @property integer $send
 */
class MailWorkpieces extends CActiveRecord
{
	public function tableName()
	{
		return 'oed_mail_workpieces';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_rule, number, form_date, send', 'required'),
			array('id_user, id_rule, number, send', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>100),
			array('template', 'safe'),
			array('id, id_user, id_rule, number, subject, template, form_date, send', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'User'=>array(self::BELONGS_TO, 'Users', 'id_user'),
                    'Rule'=>array(self::BELONGS_TO, 'MailRules', 'id_rule'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'id_rule' => 'Id Rule',
                        'addressee' => 'Адресат',
			'number' => 'Номер отправки',
			'subject' => 'Тема',
			'template' => 'Сообщение',
			'form_date' => 'Дата формирования',
			'send' => 'Отправлен',
                        'sendLink'=>'Отправка',
                        'ruleName'=>'Правило',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_rule',$this->id_rule);
		$criteria->compare('number',$this->number);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('form_date',$this->form_date,true);
		$criteria->compare('send',$this->send);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getAddressee($returnModel=false)
        {
            $email = '';
            if($this->User->email)
            {
                if($this->User->send_mailing)
                {
                    if($returnModel)
                    {
                        return $this->User;
                    }
                    else
                    {
                        $email = $this->User->email;
                    }
                }
                else
                {
                    $email = 'Отписан';
                }
            }
            elseif($this->User->Parent)
            {
                if($this->User->Parent->send_mailing)
                {
                    if($returnModel)
                    {
                        return $this->User->Parent;
                    }
                    else
                    {
                        $email =  $this->User->Parent->email;
                    }
                }
                else
                {
                    $email = 'Отписан';
                }
                
                $email .="(Родитель)";
            }
            else
            {
                return 'Нет адресата';
            }
            
            if($returnModel)
            {
                return null;
            }
            else
            {
                return $email;
            }
        }
        
        public function getSendLink()
        {
            if(!$this->send)
            {
                return CHtml::link('<i class="glyphicon glyphicon-envelope"></i>Отправить', array('/admin/mailWorkpieces/sendMessage', 'id'=>$this->id), array('class'=>'btn btn-sm btn-icon btn-info send-message'));
            }
            else
            {
                return CHtml::link(' Отправлено ', '#', array('class'=>'btn btn-sm btn-success'));
            }
        }
        
        public function getRuleName()
        {
            if($this->Rule)
            {
                return $this->Rule->name;
            }
            else
            {
                return 'Удалено';
            }
        }
}
