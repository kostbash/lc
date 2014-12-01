<?php

/**
 * This is the model class for table "oed_student_notifications_and_teacher".
 *
 * The followings are the available columns in table 'oed_student_notifications_and_teacher':
 * @property integer $id
 * @property integer $id_notification
 * @property integer $id_teacher
 * @property integer $new
 */
class StudentNotificationsAndTeacher extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_student_notifications_and_teacher';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_notification, id_teacher, id_student, new', 'required'),
			array('id_notification, id_teacher, id_student, new', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_notification, id_teacher, new', 'safe', 'on'=>'search'),
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
                    'StudentsNotifications'=>array(self::BELONGS_TO, 'StudentNotifications', 'id_notification'),
                    'Teacher'=>array(self::BELONGS_TO, 'Users', 'id_teacher'),
                    'Student'=>array(self::BELONGS_TO, 'Users', 'id_student'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_notification' => 'Id Notification',
			'id_teacher' => 'Id Teacher',
			'new' => 'New',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_notification',$this->id_notification);
		$criteria->compare('id_teacher',$this->id_teacher);
		$criteria->compare('new',$this->new);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentNotificationsAndTeacher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function CountNew($id_teacher, $id_student)
        {
            $id_teacher = (int) $id_teacher;
            $id_student = (int) $id_student;
            $criteria=new CDbCriteria;
            $criteria->compare('id_teacher', $id_teacher);
            $criteria->compare('new', 1);
            $criteria->with = 'StudentsNotifications';
            $criteria->compare('StudentsNotifications.id_user', $id_student, 'AND');
            $criteria->together = true;
            return StudentNotificationsAndTeacher::model()->count($criteria);
        }
        
        public function afterSave()
        {
            if($this->Teacher->send_notifications)
            {
                CMailer::send(
                    array(
                        'email' => $this->Teacher->email,
                        'name' => $this->Teacher->email,
                    ),
                    array(
                        'email' => 'registration@cursys.ru',
                        'name' => 'Cursys.ru'
                    ),
                    Yii::app()->name,
                    array(
                        'template' => 'students_notifications',
                        'vars' => array(
                            'student_username' => $this->Student->username,
                            'text' => $this->StudentsNotifications->text,
                        ),
                    )
                );
            }
            parent::afterSave();
        }
}
