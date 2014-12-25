<?php

/**
 * This is the model class for table "oed_children".
 *
 * The followings are the available columns in table 'oed_children':
 * @property integer $id
 * @property integer $id_parent
 * @property integer $id_child
 * @property string $child_name
 * @property string $child_surname
 * @property integer $confirm
 */
class Children extends CActiveRecord
{
        public $statuses = array(
            '0' => array(
                    'text'=>'Еще не подтвердил',
                    'color'=>'#00a',
                ),
            '1' => array(
                    'text'=>'Ваш ребенок',
                    'color'=>'#0a0',
                ),
            '2' => array(
                    'text'=>'Отклонил',
                    'color'=>'#a00',
                ),
        );
        
	public function tableName()
	{
		return 'oed_children';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            return array(
                array('id_parent, id_child, child_name, child_surname', 'required'),
                array('id_parent, id_child, status', 'numerical', 'integerOnly'=>true),
                array('child_name, child_surname', 'length', 'max'=>255),
                array('status', 'in', 'range'=>array(0,1,2)),
                array('id, id_parent, id_child, child_name, child_surname, status', 'safe', 'on'=>'search'),
                array('id, id_parent, id_child, status', 'unsafe', 'on'=>'update'),
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
                    'Parent'=>array(self::BELONGS_TO, 'Users', 'id_parent'),
                    'Child'=>array(self::BELONGS_TO, 'Users', 'id_child'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_parent' => 'Id Parent',
			'id_child' => 'Id Child',
			'child_name' => 'Имя',
			'child_surname' => 'Фамилия',
			'status' => 'status',
			'statusText' => 'Статус',
                        'newNotifications' => 'Оповещения',
		);
	}
        
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_parent', Yii::app()->user->id);
		$criteria->compare('id_child',$this->id_child);
		$criteria->compare('child_name',$this->child_name,true);
		$criteria->compare('child_surname',$this->child_surname,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
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
                $count = StudentNotificationsAndTeacher::CountNew($this->id_parent, $this->id_child);
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
        
        public function afterDelete() {
            UserExercisesLogsAndTeacher::model()->deleteAllByAttributes(array('id_teacher'=>$this->id_parent, 'id_student'=>$this->id_child));
            StudentNotificationsAndTeacher::model()->deleteAllByAttributes(array('id_teacher'=>$this->id_parent, 'id_student'=>$this->id_child));
            parent::afterDelete();
        }
        
        public static function countNewParents()
        {
            return Children::model()->count("id_child=:id_child AND status=:status", array('id_child'=>Yii::app()->user->id, 'status'=>0));
        }
        
        public static function newParents()
        {
            return Children::model()->findAllByAttributes(array('id_child'=>Yii::app()->user->id, 'status'=>0));
        }
}
