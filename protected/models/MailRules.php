<?php

/**
 * This is the model class for table "oed_mail_rules".
 *
 * The followings are the available columns in table 'oed_mail_rules':
 * @property integer $id
 * @property string $name
 * @property integer $use_number
 * @property integer $interval
 * @property integer $passed_reg_days
 * @property integer $unactivity_days
 * @property integer $number_of_passed_lessons
 * @property integer $passed_course
 * @property integer $number_of_passed_courses
 */
class MailRules extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_mail_rules';
	}
        
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, use_number, interval', 'required'),
			array('use_number, interval, passed_reg_days, unactivity_days, number_of_passed_lessons, passed_course, number_of_passed_courses, unpassed_check_test', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('roles', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, use_number, interval, passed_reg_days, unactivity_days, number_of_passed_lessons, passed_course, number_of_passed_courses, unpassed_check_test', 'safe', 'on'=>'search'),
		);
	}
        
        public static $defaultValues = array(
            'use_number' => '1',
            'interval' => '3',
            'passed_reg_days' => '0',
            'unactivity_days' => '3',
            'unpassed_check_test' => '1',
            'number_of_passed_lessons' => '1',
            'number_of_passed_courses' => '1',
        );

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'PassedCourse'=>array(self::BELONGS_TO, 'Courses', 'passed_course'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'use_number' => 'Максимальное число применений',
			'interval' => 'Минимальный интервал',
                        'roles'=>'Роли',
			'passed_reg_days' => 'Зарегистрирован',
			'unactivity_days' => 'Не активен',
			'number_of_passed_lessons' => 'Пройдено уроков',
			'passed_course' => 'Пройден определенный курс',
			'number_of_passed_courses' => 'Пройдено курсов',
			'unpassed_check_test' => 'Не пройден проверочный тест',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('use_number',$this->use_number);
		$criteria->compare('interval',$this->interval);
		$criteria->compare('passed_reg_days',$this->passed_reg_days);
		$criteria->compare('unactivity_days',$this->unactivity_days);
		$criteria->compare('passed_course',$this->passed_course);
                $criteria->compare('number_of_passed_courses',$this->number_of_passed_courses);
		$criteria->compare('number_of_passed_lessons',$this->number_of_passed_lessons);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function selectUsers()
        {
            $useTables = array('users');
            $sqlTables = array();
            $prefix = 'oed_';
            $condition = '1';
            $select = array('user.*');
            
            
            $roles = implode(', ', unserialize($this->roles));
            
            if(!$roles)
            {
                return array();
            }
            else
            {
                $condition .= " AND ";
                $condition .= "`role` IN ($roles)";
            }
            
            $condition .= " AND ";
            $condition .= "(`email` IS NOT NULL OR EXISTS(SELECT * FROM `{$prefix}children` WHERE `id_child`=user.id AND `status`=2))";
            
            if($this->use_number)
            {
                $condition .= " AND ";
                $condition .= "(SELECT COUNT(*) FROM `{$prefix}mail_workpieces` WHERE id_user=user.id AND id_rule='$this->id') < '$this->use_number'";
            }
            if($this->interval!='')
            {
                $condition .= " AND ";
                $condition .= "((@lastwp:=(SELECT form_date FROM `{$prefix}mail_workpieces` WHERE id_user=user.id AND id_rule='$this->id' ORDER BY number DESC LIMIT 1)) IS NULL OR DATEDIFF(NOW(), @lastwp) = '$this->interval')";
            }
            if($this->passed_reg_days!='')
            {
                $condition .= " AND ";
                $condition .= "DATEDIFF(NOW(), `registration_day`) = '$this->passed_reg_days'";
            }
            if($this->unactivity_days!='')
            {
                $condition .= " AND ";
                $condition .= "DATEDIFF(NOW(), `last_activity`) = '$this->unactivity_days'";
            }
            if($this->passed_course!='')
            {
                $condition .= " AND ";
                $condition .= "course_user.id_course = '$this->passed_course'";
                
                $condition .= " AND ";
                $condition .= "course_user.status=2";
                $useTables[] = 'courses_and_users';
            }
            if($this->number_of_passed_courses!='')
            {
                $condition .= " AND ";
                $condition .= "(SELECT COUNT(*) FROM `{$prefix}courses_and_users` WHERE id_user=user.id AND status=2) = '$this->number_of_passed_courses'";
            }
            if($this->number_of_passed_lessons!='')
            {
                $condition .= " AND ";
                $condition .= "(SELECT COUNT(*) FROM `{$prefix}user_and_lessons` WHERE id_user=user.id AND passed=1) = '$this->number_of_passed_lessons'";
            }
            
            if(in_array('users',$useTables))
            {
                $sqlTables[] = "`{$prefix}users` as user";
            }
            
            if(in_array('courses_and_users',$useTables))
            {
                $sqlTables[] = "`{$prefix}courses_and_users` as course_user";
                $condition .= " AND ";
                $condition .= "course_user.id_user=user.id";
            }
             
            $sql = "SELECT " . implode(', ', $select) . " FROM " . implode(', ', $sqlTables) . " WHERE $condition";
            
            return Users::model()->findAllBySql($sql);
        }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
