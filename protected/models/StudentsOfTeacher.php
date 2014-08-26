<?php

/**
 * This is the model class for table "oed_students_of_teacher".
 *
 * The followings are the available columns in table 'oed_students_of_teacher':
 * @property integer $id
 * @property integer $id_teacher
 * @property integer $id_student
 * @property string $student_name
 * @property string $student_surname
 * @property integer $confirm
 */
class StudentsOfTeacher extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_students_of_teacher';
	}

        public $statuses = array(
            '0' => array(
                    'text'=>'Еще не подтвердил',
                    'color'=>'#00a',
                ),
            '1' => array(
                    'text'=>'Ваш ученик',
                    'color'=>'#0a0',
                ),
            '2' => array(
                    'text'=>'Отклонил',
                    'color'=>'#a00',
                ),
        );
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_teacher, id_student, student_name, student_surname', 'required'),
			array('id_teacher, id_student, status', 'numerical', 'integerOnly'=>true),
			array('student_name, student_surname', 'length', 'max'=>255),
                        array('status', 'in', 'range'=>array(0,1,2)),
			array('id, id_teacher, id_student, student_name, student_surname, status', 'safe', 'on'=>'search'),
			array('id, id_teacher, id_student, status', 'unsafe', 'on'=>'update'),
			array('confirm, regect', 'unsafe'),
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
			'id_teacher' => 'Id Teacher',
			'id_student' => 'Id Student',
			'student_name' => 'Имя',
			'student_surname' => 'Фамилия',
			'status' => 'status',
			'statusText' => 'Статус',
                        'newNotifications' => 'Оповещения',
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
		$criteria->compare('id_teacher', Yii::app()->user->id);
		$criteria->compare('id_student', $this->id_student);
		$criteria->compare('student_name', $this->student_name,true);
		$criteria->compare('student_surname', $this->student_surname,true);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentsOfTeacher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getStatusText()
        {
            $status = $this->statuses[$this->status];
            return "<p style='color:{$status['color']}'>{$status['text']}</p>";
        }
        
        public function getNewNotifications()
        {
            if($this->status==1)
            {
                $count = StudentNotificationsAndTeacher::CountNew($this->id_teacher, $this->id_student);
                if($count==0)
                {
                    return "<p style='color:#a00'>Нет новых уведомлений</p>";
                }
                else
                {
                    return "<p style='color:#0a0'>Кол-во новых уведомлений: $count</p>";
                }
            } else {
                return "<p style='color:#a00'>Нет</p>";
            }
        }
        
        
        public static function countNewTeachers()
        {
            return StudentsOfTeacher::model()->count("id_student=:id_student AND status=:status", array('id_student'=>Yii::app()->user->id, 'status'=>0));
        }
        
        public static function newTeachers()
        {
            return StudentsOfTeacher::model()->findAllByAttributes(array('id_student'=>Yii::app()->user->id, 'status'=>0));
        }
        
        public function afterDelete() {
            UserExercisesLogsAndTeacher::model()->deleteAllByAttributes(array('id_teacher'=>$this->id_teacher, 'id_student'=>$this->id_student));
            StudentNotificationsAndTeacher::model()->deleteAllByAttributes(array('id_teacher'=>$this->id_teacher, 'id_student'=>$this->id_student));
            parent::afterDelete();
        }
}
