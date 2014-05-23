<?php

/**
 * This is the model class for table "oed_users".
 *
 * The followings are the available columns in table 'oed_users':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $type
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    
        public $checkPassword;
        public $sendOnMail;
        public $rememberMe;
        public static $placeImage = array(
            1 => 'first-place.png',
            2 => 'second-place.png',
            3 => 'three-place.png',
        );
    
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
			array('email, password, checkPassword, type', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>100),
			array('email', 'email', 'message'=>'Проверьте правильность введения адреса почты'),
                        array('sendOnMail, rememberMe', 'boolean'),
			array('password', 'length', 'max'=>32),
			array('progress_key', 'length', 'max'=>20),
                    	array('email', 'unique', 'message'=>'Указанный почтовый адрес уже используется'),
                        array('checkPassword', 'compare', 'compareAttribute'=>'password'),
			array('id, email, password, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Ваш e-mail',
			'password' => 'Пароль',
			'checkPassword' => 'Повторите пароль',
                        'registration_day' => 'Дата регистрации',
                        'last_activity' => 'Последняя активность',
                        'sendOnMail' => 'Отправить новый пароль на e-mail',
			'type' => 'Type',
                        'countPassLessons' => 'Число пройденных уроков',
                        'rememberMe' => 'Узнавать меня на этом устройстве',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('type',2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
            $admins = Users::model()->findAllByAttributes(array('type'=>1));
            $mass = array();
            foreach($admins as $admin) {
                $mass[] = $admin->email;
            }
            return $mass;
        }
        
        public static function UserType()
        {
            if(!Yii::app()->user->isGuest) {
                $user = Users::model()->findByPk(Yii::app()->user->id);
                if($user)
                    return $user->type;
            }
            return false;
        }
        
        public function getCountPassLessons() {
            return UserAndLessons::model()->countByAttributes(array('id_user'=>$this->id, 'passed'=>1));
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
                $exerciseGroups = UserExerciseGroupSkills::model()->findAllByAttributes(array('id_user_and_lesson'=>$userLesson->id));
                foreach($exerciseGroups as $exerciseGroup)
                {
                    $right += $exerciseGroup->right_answers;
                    $all += $exerciseGroup->number_all;
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
            $this->progress_key = substr(md5(Yii::app()->params['beginSalt'].$this->email.Yii::app()->params['endSalt']), 0, 25);
            $this->save(false);
            return $this->progress_key;
        }
}
