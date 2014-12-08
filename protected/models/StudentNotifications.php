<?php

/**
 * This is the model class for table "oed_student_notifications".
 *
 * The followings are the available columns in table 'oed_student_notifications':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_type
 * @property string $date
 * @property string $time
 * @property string $text
 */
class StudentNotifications extends CActiveRecord
{
        public $new;
        public $lookAdmin;
        
	public function tableName()
	{
		return 'oed_student_notifications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_type, date, time, text', 'required'),
			array('id_user, id_type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, id_type, date, time, text, new, lookAdmin', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'NotificationType'=>array(self::BELONGS_TO, 'Notifications', 'id_type'),
                    'NotificationsAndTeachers'=>array(self::HAS_MANY, 'StudentNotificationsAndTeacher', 'id_notification'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'id_type' => 'Id Type',
			'date' => 'Дата',
			'niceDate' => 'Дата',
			'time' => 'Время',
			'text' => 'Детали',
                        'notificationName' => 'Тип',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_type',$this->id_type);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('text',$this->text,true);
                
                if(!$this->lookAdmin)
                {
                    $criteria->with = 'NotificationsAndTeachers';
                    $criteria->compare('NotificationsAndTeachers.id_teacher', Yii::app()->user->id, 'AND');
                    $criteria->compare('NotificationsAndTeachers.new', $this->new, 'AND');
                    $criteria->order = 'NotificationsAndTeachers.new DESC, date DESC, time DESC';
                    $criteria->together = true;
                }
                else
                {
                    $criteria->order = 'date DESC, time DESC';
                }
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        // удаляем информацию о том что запись новая
        public function madeOldRecord(CActiveDataProvider $dataProvider)
        {
            $notifications = $dataProvider->getData();
            foreach($notifications as $notification)
            {
                if($notification->isNew)
                {
                    $teacherNotification = StudentNotificationsAndTeacher::model()->findByAttributes(array('id_notification'=>$notification->id, 'id_teacher'=>Yii::app()->user->id));
                    $teacherNotification->new = 0;
                    $teacherNotification->save();
                }
            }
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getNiceDate()
        {
            return date('d.m.Y', strtotime($this->date));
        }
        
        public function getNotificationName()
        {
            $text = "<p style='color:{$this->NotificationType->color}'>";
                $text .= $this->NotificationType->name;
            $text .= "</p>";
            return $text;
        }
        
        public function getIsNew()
        {
            return StudentNotificationsAndTeacher::model()->exists("id_notification=:id_notification AND id_teacher=:id_teacher AND new=:new", array('id_notification'=>$this->id, 'id_teacher'=>Yii::app()->user->id, 'new'=>1));
        }
        
        public function getClassLog()
        {
            if($this->isNew)
            {
                return 'new-log';
            }
        }
}
