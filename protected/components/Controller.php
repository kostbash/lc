<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        public $_course;
        public $_lesson;
        public $_block;
        
        public function getCourse()
        {
            $id = null;
            if($this->_lesson)
            {
                $id = Lessons::model()->findByPk($this->_lesson)->course_creator_id;
            }
            elseif($this->_course)
            {
                $id = $this->_course;
            }
            if($id)
                return "Курс [$id] ".Courses::model()->findByPk($id)->name;
            else
                return null;
        }
        
        public function getLesson()
        {
            if($this->_lesson)
                return "Урок [$this->_lesson] ".Lessons::model()->findByPk($this->_lesson)->name;
            else 
                return null;
        }
        
        public function getBlock()
        {
            if($this->_block)
                return "Блок [$this->_block] ".GroupOfExercises::model()->findByPk($this->_block)->name;
            else
                return null;
        }
}

if(Yii::app()->user->id)
{
    $user = Users::model()->findByPk(Yii::app()->user->id);
    if(date('Y-m-d') > $user->last_activity)
    {
        $user->stamina = date('Y-m-d', strtotime("$user->last_activity +1 day"))==date('Y-m-d') ? $user->stamina+1 : 1;
        $user->last_activity = date('Y-m-d');
        $user->save(false);
    }
}