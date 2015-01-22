<?php

/**
 * This is the model class for table "oed_users".
 *
 * The followings are the available columns in table 'oed_users':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $role
 */
class Users extends CActiveRecord
{ 
        public static $rolesNames = array(
            '1' => 'admin',
            '2' => 'student',
            '3' => 'teacher',
            '4' => 'parent',
        );
        
        public static $rolesRusNames = array(
            '1' => 'Админ',
            '2' => 'Ученик',
            '3' => 'Учитель',
            '4' => 'Родитель',
        );
        
        public $countLessons;
        
        public static $places = array(
            '1' => 'first',
            '2' => 'second',
            '3' => 'third',
        );
        
        public static $defaultRole = 2;
        
        public $temporary_password;
        
        public $checkPassword;
        public $sendOnMail;
        public $rememberMe;
        public static $placeImage = array(
            1 => 'first-place.png',
            2 => 'second-place.png',
            3 => 'third-place.png',
        );
        
        public static $recoveryQuestions = array(
            1 => 'Девичья фамилия матери',
            2 => 'Имя любимого животного',
            3 => 'Любимое блюдо',
        );
        
        public static $listCountLessons = array(
            '=0' => '0',
            '=1' => '1',
            '>1' => 'Больше 1',
        );
        
        public $addParentOnReg = false;
        public $createParentOnReg = false;
        public $emailParentOnReg;
        
        public $last_unactivity;
        
        
        
    
	public function tableName()
	{
		return 'oed_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, checkPassword, role', 'required'),
			array('id_recovery_question, recovery_answer', 'requiredStudent'),
                        array('email', 'requiredParent'),
			array('role, id_recovery_question', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>100),
			array('role', 'in', 'range'=>array(1,2,3,4)),
                        array('email, emailParentOnReg', 'email', 'message'=>'Проверьте правильность введенного адреса почты'),
                        array('sendOnMail, rememberMe, send_notifications, addParentOnReg, createParentOnReg, send_mailing', 'boolean'),
			array('password, temporary_password, unsubscribe_key', 'length', 'max'=>32),
			array('recovery_answer', 'length', 'max'=>100),
			array('progress_key', 'length', 'max'=>25),
                        array('emailParentOnReg', 'needEmailOnReg'),
                        array('emailParentOnReg', 'differentWithChild'),
                        array('emailParentOnReg', 'uniqueParentEmailOnReg'),
                    	array('email', 'unique', 'message'=>'Указанный почтовый адрес уже используется'),
                    	array('username', 'unique', 'message'=>'Указанный логин уже используется'),
                    	array('username', 'match', 'pattern'=>'/^[\w\d_.@]{4,}$/i', 'message'=>'Логин должен быть не менее 4 символов и состоять из латинских букв, цифр, знака подчеркивания, точки и @'),
                        array('checkPassword', 'compare', 'compareAttribute'=>'password'),
			array('id, username, email, password, role, last_activity, last_unactivity, registration_day, countLessons', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Courses'=>array(self::MANY_MANY, 'Courses', 'oed_courses_and_users(id_user, id_course)'),
		);
	}
        
        public function requiredParent($attr, $params)
        {
            if($this->role==4 && $this->$attr=='')
            {
                $this->addError($attr, 'Электронный адрес не может быть пустым');
            }
        }
        
        public function requiredStudent($attr, $params)
        {
            if($this->role==2 && $this->$attr=='')
            {
                $this->addError($attr, 'Необходимо заполнить поле "'.$this->getAttributeLabel($attr).'"');
            }
        }
        
        public function needEmailOnReg($attr, $params)
        {
            if($this->role==2 && $this->addParentOnReg && $this->$attr=='')
            {
                $this->addError($attr, 'Введите Ваш e-mail');
            }
        }
        
        public function differentWithChild($attr, $params)
        {
            if($this->username == $this->emailParentOnReg)
                $this->addError($attr, 'Логин родителя должен отличаться от логина ученика.');
        }
        
        public function uniqueParentEmailOnReg($attr, $params)
        {
            if($this->role==2 && $this->addParentOnReg && $this->createParentOnReg)
            {
                $exists = Users::model()->exists('username=:username', array('username'=>$this->$attr));
                if($exists)
                {
                    $this->addError($attr, 'Пользователь с таким e-mail уже существует');
                }
            }
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'E-mail',
			'username' => 'Псевдоним',
			'password' => 'Пароль',
			'checkPassword' => 'Повторите пароль',
                        'registration_day' => 'Дата регистрации',
                        'last_activity' => 'Последняя активность',
                        'last_unactivity' => 'Не активен',
                        'sendOnMail' => 'Отправить новый пароль на e-mail',
			'role' => 'Роль',
			'rusRoleName' => 'Роль',
                        'countPassLessons' => 'Число пройденных уроков',
                        'countPassCourses' => 'Число пройденных курсов',
                        'rememberMe' => 'Узнавать меня',
                        'send_notifications'=>'Включить оповещения о результатах ребенка на e-mail',
                        'id_recovery_question'=>'Выберите контрольный вопрос',
                        'recovery_answer'=>'Ваш ответ на контрольный вопрос',
                        'addParentOnReg'=>'Для родителя: иметь возможность отслеживать прогресс обучения',
                        'createParentOnReg'=>'У Вас еще нет аккаунта родителя? Установите этот флажок, чтобы создать новый аккаунт',
                        'emailParentOnReg' => 'Введите Ваш e-mail',
                        'myParent' => 'Родитель',
                        'countLessons'=>'Пройдено уроков',
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

                $today = date('Y-m-d H:i:s');
                
		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
                $criteria->compare('role', $this->role, true);
		$criteria->compare('email',$this->email,true);
                
                $criteria->addNotInCondition('role', array(1));
                
                if($this->registration_day)
                {
                    if(date('Y-m-d 00:00:00', strtotime('- 1day'))==$this->registration_day) // если фильтр вчера
                    {
                        $criteria->addCondition("DATE_FORMAT(`registration_day`,'%m-%d-%Y') = DATE_FORMAT(:registration_day, '%m-%d-%Y')");
                    }
                    else
                    {
                        $criteria->addCondition("`registration_day` >= :registration_day AND `registration_day`<='$today'");
                    }
                    $criteria->params['registration_day'] = $this->registration_day;
                }
                
                if($this->last_activity)
                {
                    $criteria->addCondition("`last_activity` >= :last_activity AND `last_activity` <='$today'");
                    $criteria->params['last_activity'] = $this->last_activity;
                }
                
                if($this->last_unactivity)
                {
                    $criteria->addCondition("`last_activity` < :last_unactivity");
                    $criteria->params['last_unactivity'] = $this->last_unactivity;
                }
                
                if($this->countLessons && isset(self::$listCountLessons[$this->countLessons]))
                {
                    $criteria->addCondition("(SELECT count(*) FROM `oed_user_and_lessons` WHERE id_user=t.id AND passed=1) $this->countLessons");
                }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination'=> array('pageSize'=>50),
                        'sort'=>array(
                            'defaultOrder'=>'`last_activity` DESC',
                        ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function Admins()
        {
            $admins = Users::model()->findAllByAttributes(array('role'=>1));
            $mass = array();
            foreach($admins as $admin) {
                $mass[] = $admin->username;
            }
            return $mass;
        }
        
        public static function UserType()
        {
            if(!Yii::app()->user->isGuest) {
                $user = Users::model()->findByPk(Yii::app()->user->id);
                if($user)
                    return $user->role;
            }
            return false;
        }
        
        public function getCountPassLessons() {
            return UserAndLessons::model()->countByAttributes(array('id_user'=>$this->id, 'passed'=>1));
        }
        
        public function getCountPassCourses() {
            return CoursesAndUsers::model()->countByAttributes(array('id_user'=>$this->id, 'status'=>2));
        }
        
        public function getCountPassTest()
        {
            $userLessons = UserAndLessons::model()->findAllByAttributes(array('id_user'=>$this->id));
            $count = 0;
            foreach($userLessons as $userLesson)
            {
                $exerciseGroups = UserAndExerciseGroups::model()->findAllByAttributes(array('id_user_and_lesson'=>$userLesson->id, 'passed'=>1));
                foreach($exerciseGroups as $exerciseGroup)
                {
                    if($exerciseGroup->Group->type == 2)
                        $count++;
                }
            }
            return $count;
        }
        
        public function getAveragePoint()
        {
            $userLessons = UserAndLessons::model()->findAllByAttributes(array('id_user'=>$this->id));
            $right = 0;
            $all = 0;
            foreach($userLessons as $userLesson)
            {
                $userSkills = UserExerciseGroupSkills::model()->findAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                foreach($userSkills as $userSkill)
                {
                    $right += $userSkill->right_answers;
                    $all += $userSkill->number_all;
                }
            }
            if($all==0)
                return 0;
            return round($right/$all, 2) * 100;
        }
        
        public function getHasSkills()
        {
            $userLessons = UserAndLessons::model()->findAllByAttributes(array('id_user'=>$this->id, 'passed'=>1));
            $count = array();
            foreach($userLessons as $userLesson)
            {
                foreach($userLesson->Lesson->Skills as $skill)
                {
                    if($skill->type == 2)
                        $count[$skill->id] = true;
                }
            }
            return count($count);
        }
        
        public function placeByAttribute($attr)
        {
            if(!$this->hasAttribute($attr))
                return false;
            $users = Users::model()->findAll("`$attr` >=:$attr ORDER BY `$attr` DESC, `last_activity` DESC", array("$attr"=>$this->$attr));
            $count = 0;
            foreach($users as $user)
            {
                $count++;
                if($user->id == $this->id)
                    return $count;
            }
        }
        
        public function getProgressKey() {
            if($this->progress_key)
                return $this->progress_key;
            $this->progress_key = substr(md5(Yii::app()->params['beginSalt'].$this->username.Yii::app()->params['endSalt']), 0, 25);
            $this->save(false);
            return $this->progress_key;
        }
        
        // сохраняем достижения пользователя
        public static function saveAchievements()
        {
            $user = Users::model()->findByAttributes(array('id'=>Yii::app()->user->id));
            if($user)
            {
                $user->experience = $user->CountPassTest;
                $user->accuracy = $user->AveragePoint;
                $user->wisdom = $user->hasSkills;
                $user->save(false);
            }
        }
        
        public function getRoleName()
        {
            if(self::$rolesNames[$this->role])
                return self::$rolesNames[$this->role];
        }
        
        public function getParentRelation()
        {
            return Children::model()->findByAttributes(array('id_child'=>$this->id, 'status'=>1));
        }
        
        public function getTeachersRelations()
        {
            return StudentsOfTeacher::model()->findAllByAttributes(array('id_student'=>$this->id, 'status'=>1));
        }
        
        public function registration($attributes)
        {
            $this->attributes = $attributes;
            $this->temporary_password = substr(md5('lol'.uniqid().'azaza'), 0, 10);
            $this->password = $this->temporary_password;
            $this->checkPassword = $this->temporary_password;
            
            if($this->role==4)
            {
                $this->username = $this->email;
            }
            
            $validate = $this->validate();
            
            if($validate && $this->role==2 && $this->addParentOnReg)
            {
                if($this->createParentOnReg)
                {
                    $parent = new Users;
                    $parent->role = 4;
                    $parent->registration(array('email'=>$this->emailParentOnReg));
                }
                else
                {
                    $parent = Users::model()->findByAttributes(array('username'=>$this->emailParentOnReg, 'role'=>4));
                    if(!$parent)
                    {
                        $this->addError('emailParentOnReg', 'Не зарегистрирован родитель с таким e-mail');
                        $validate = false;
                    }
                }
            }
            
            if($validate)
            {
                $this->password = md5(Yii::app()->params['beginSalt'].$this->password.Yii::app()->params['endSalt']);
                $this->progress_key = substr(md5(Yii::app()->params['beginSalt'].$this->username.Yii::app()->params['endSalt']), 0, 25);
                $this->registration_day = date('Y-m-d H:i:s');

                if($this->role==4)
                {
                    $this->confirm_key = md5(Yii::app()->params['beginSalt'].uniqid().Yii::app()->params['endSalt']);
                    CMailer::send(
                        array(
                            'email' => $this->email,
                            'name' => $this->email,
                        ),
                        array(
                            'email' => 'registration@cursys.ru',
                            'name' => 'Cursys.ru'
                        ),
                        Yii::app()->name,
                        array(
                            'template' => 'member_register',
                            'vars' => array(
                                'activate_link' => CHtml::link('Подтвердите профиль и перейдите к курсу', array('users/activate', 'key' => $this->confirm_key)),
                                'temporary_password' => $this->temporary_password,
                                'site_name'=>Yii::app()->name,
                            ),
                        )
                    );
                }
                $this->save(false);
                
                if($parent)
                {
                    $children = new Children;
                    $children->id_child = $this->id;
                    $children->id_parent = $parent->id;
                    $children->child_name = 'Без имени';
                    $children->child_surname = 'Без фамилии';
                    $children->status = 1;
                    $children->save();
                }
            }
            return $validate;
        }
        
        public static function Students()
        {
            $childs=Yii::app()->db->createCommand("SELECT DISTINCT `id_child` FROM `oed_children`")->queryAll();
            $students=Yii::app()->db->createCommand("SELECT DISTINCT `id_student` FROM `oed_students_of_teacher`")->queryAll();
            $allStudents = array();
            foreach($childs as $child)
            {
                $id_child = $child['id_child'];
                if(!$allStudents[$id_child])
                    $allStudents[$id_child] = $id_child;
            }
            foreach($students as $student)
            {
                $id_student = $student['id_student'];
                if(!$allStudents[$id_student])
                    $allStudents[$id_student] = $id_student;
            }
            
            return $allStudents;
        }
        
        public static function StudentTeachers($id_student)
        {
            $id_student = (int) $id_student;
            $parents=Yii::app()->db->createCommand("SELECT DISTINCT `id_parent` FROM `oed_children` WHERE id_child='$id_student'")->queryAll();
            $teachers=Yii::app()->db->createCommand("SELECT DISTINCT `id_teacher` FROM `oed_students_of_teacher` WHERE id_student='$id_student'")->queryAll();
            $allTeachers = array();
            foreach($parents as $parent)
            {
                $id_parent = $parent['id_parent'];
                if(!$allTeachers[$id_parent])
                    $allTeachers[$id_parent] = $id_parent;
            }
            foreach($teachers as $teacher)
            {
                $id_teacher = $teacher['id_teacher'];
                if(!$allTeachers[$id_teacher])
                    !$allTeachers[$id_teacher] = $id_teacher;
            }
            return $allTeachers;
        }
        
        public function getRusRoleName()
        {
            return self::$rolesRusNames[$this->role];
        }
        
        public static function getLogoLink()
        {
            if(Yii::app()->user->isGuest)
            {
                return '/';
            }
            else
            {
               if(Yii::app()->user->checkAccess('admin'))
                   return Yii::app()->createUrl('/admin/courses/index');
               else
                   return Yii::app()->createUrl('courses/list');
            }
        }
        
        public function lastCourses($status)
        {
            $status = (int) $status;
            $criteria=new CDbCriteria;
            $criteria->with = array('Users');
            $criteria->together = true;
            $criteria->compare('Users.id', Yii::app()->user->id);
            if($status===1)
            {
                $criteria->compare('status', $status);
                $criteria->order = 'activity_date ASC';
            }
            elseif($status===2)
            {
                $criteria->compare('status', $status);
                $criteria->order = 'passed_date ASC';
            }
            return Courses::model()->findAll($criteria);
        }
        
        public function getLastActiveCourse()
        {
            $lastActive = CoursesAndUsers::model()->find(array(
                'condition'=> 'id_user=:id_user',
                'params'=>array('id_user'=>$this->id),
                'order'=>'`activity_date` DESC',
            ));
            if($lastActive)
            {
                return Courses::model()->findByPk($lastActive->id_course);
            }
        }
        
        public function getNiceRecoveryQuestion()
        {
            return self::$recoveryQuestions[$this->id_recovery_question];
        }
        
        public function getMyParent()
        {
            if($this->role==2)
            {
                $parent = Children::model()->findByAttributes(array('id_child'=>$this->id));
                if($parent)
                {
                    return $parent->Parent->email;
                }
                return 'Нет';
            }
            return '-';
        }
        
        public function getParent()
        {
            if($this->role==2)
            {
                $parent = Children::model()->findByAttributes(array('id_child'=>$this->id));
                if($parent)
                {
                    return $parent->Parent;
                }
            }
            return NULL;
        }
        
        public static function listLastActivity()
        {
            $list = array();
            $list[date('Y-m-d 00:00:00', strtotime('- 1day'))] = 'Сегодня/Вчера';
            $list[date('Y-m-d 00:00:00', strtotime('- 3day'))] = 'Сегодня/Последние 3 дня';
            return $list;
        }
        
        public static function listLastUnActivity()
        {
            $list = array();
            $list[date('Y-m-d 00:00:00', strtotime('- 1day'))] = 'Сегодня и вчера';
            $list[date('Y-m-d 00:00:00', strtotime('- 3day'))] = 'Сегодня и последние 3 дня';
            $list[date('Y-m-d 00:00:00', strtotime('- 7day'))] = 'Более 7-ми дней';
            $list[date('Y-m-d 00:00:00', strtotime('- 1month'))] = 'Более месяца';
            return $list;
        }
        
        public static function listRegistrationDates()
        {
            $list = array();
            $list[date('Y-m-d 00:00:00')] = 'Сегодня';
            $list[date('Y-m-d 00:00:00', strtotime('- 1day'))] = 'Вчера';
            $list[date('Y-m-d 00:00:00', strtotime('- 7day'))] = '7 дней';
            $list[date('Y-m-d 00:00:00', strtotime('- 1month'))] = 'Месяц';
            return $list;
        }
        
        public function numberWorkPiece($id_rule)
        {
            $query = "SELECT number FROM `oed_mail_workpieces` WHERE `id_user`='$this->id' AND `id_rule` = '$id_rule' ORDER BY `number` DESC LIMIT 1";
            $result = Yii::app()->db->createCommand($query)->queryAll();
            return ((int) $result[0]['number'])+1;
        }
}
