<?php

/**
 * This is the model class for table "oed_courses_and_users".
 *
 * The followings are the available columns in table 'oed_courses_and_users':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_course
 */
class CoursesAndUsers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_courses_and_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id_user, id_course', 'required'),
                        array('activity_date, passed_date', 'date', 'format'=>'yyyy-mm-dd hh:mm:ss'),
			array('id_user, id_course, is_begin', 'numerical', 'integerOnly'=>true),
			array('id, id_user, id_course', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
                    'Course'=>array(self::BELONGS_TO, 'Courses', 'id_course'),
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
			'id_course' => 'Id Course',
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_course',$this->id_course);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoursesAndUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        public function OnChangeCourse()
        {
            $changeDate = strtotime($this->Course->change_date);
            $lastActivityDate = strtotime($this->last_activity_date);
            
            if($changeDate >= $lastActivityDate)
            {
                $query = "SELECT themes.id_course, lessons.id_group, lessons.id_lesson FROM `oed_courses` course, `oed_course_and_lesson_group` themes, `oed_group_and_lessons` lessons
                        WHERE themes.id_course=course.id AND lessons.id_group=themes.id_group_lesson AND course.id='$this->id_course'
                        ORDER BY themes.order ASC, lessons.order ASC";
                $lessonsAttrs = Yii::app()->db->createCommand($query)->queryAll();

                $lastUserLesson = $this->Course->lastUserLesson;

                // убираем проверочный урок
                if($lessonsAttrs[0])
                {
                    $lessonsAttrs[0]['id_user'] = $this->id_user;
                    $testLesson = UserAndLessons::model()->findByAttributes($lessonsAttrs[0]);
                    if($testLesson)
                    {
                        $testLesson->delete();
                    }
                    unset($lessonsAttrs[0]);
                }
                
                if($lastUserLesson)
                {
                    foreach($lessonsAttrs as $lessonAttrs)
                    {
                        if($lastUserLesson->id_lesson != $lessonAttrs['id_lesson'])
                        {
                            $lessonAttrs['id_user'] = $this->id_user;
                            $userAndLesson = UserAndLessons::model()->findByAttributes($lessonAttrs);

                            if(!$userAndLesson)
                            {
                                $userAndLesson = new UserAndLessons;
                                $userAndLesson->attributes = $lessonAttrs;
                                $userAndLesson->last_activity_date = date('Y-m-d H:i:s');
                            }
                            
                            $makePassed = false;

                            if($userAndLesson->isNewRecord || !$userAndLesson->passed)
                            {
                                $userAndLesson->passed = 1;
                                $userAndLesson->save();
                                $makePassed = true;
                            }

                            $userAndLesson->OnChangeLesson($makePassed);
                        }
                        else
                        {
                            $lastUserLesson->onChangeLesson();
                            break;
                        }
                    }
                }
            }
            
            $this->last_activity_date = date('Y-m-d H:i:s');
            $this->save();
        }   
}
