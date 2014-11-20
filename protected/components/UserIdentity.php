<?php

class UserIdentity extends CUserIdentity
{
    private $_id;
    
    public function authenticate($passwordMD5 = false)
    {
        $user=Users::model()->findByAttributes(array('username'=>$this->username));
        $conditionPassword = $passwordMD5 ? $user->password !== $this->password : $user->password !== md5(Yii::app()->params['beginSalt'].$this->password.Yii::app()->params['endSalt']);
        if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif($user->confirm_key)
        {
            Yii::app()->request->redirect(Yii::app()->createUrl('users/accessDenyByConfirm'));
        }
        elseif($conditionPassword)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id=$user->id;
            $this->setState('username', $user->username);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}