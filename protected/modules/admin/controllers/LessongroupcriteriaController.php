<?php

class LessongroupcriteriaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete', 'changeordercriteria', 'skillsnotidsajax', 'savechange'),
				'roles'=>array('editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        public function actionChangeOrderCriteria($id_group)
        {
            if($_POST['id_criteria'] && $_POST['id_sibling_criteria'])
            {
                $current = LessonGroupCriteria::model()->findByAttributes(array('id_group'=>$id_group, 'id'=>$_POST['id_criteria']));
                $sibling = LessonGroupCriteria::model()->findByAttributes(array('id_group'=>$id_group, 'id'=>$_POST['id_sibling_criteria']));
                if($current && $sibling)
                {
                        $var = $current->order;
                        $current->order = $sibling->order;
                        $sibling->order = $var;
                        if($current->save() && $sibling->save())
                            echo 1;
                }
            }
        }

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
                if($model)
                {
                    LessonGroupCriteriaAndSkills::model()->deleteAllByAttributes(array('id_lesson_criteria'=>$model->id));
                    $model->delete();
                }
                    
	}
        
        public function actionSkillsNotIdsAjax()
	{
            $criteria = new CDbCriteria;
            
            if (isset($_POST['term']))// если переданы символы
            {
                $criteria->condition = '`name` LIKE :name';
                $criteria->params['name'] = '%' . $_POST['term'] . '%';
            }

            if($_POST['LessonGroupCriteria'])
            {
                foreach($_POST['LessonGroupCriteria'] as $id_criteria => $attributes)
                {
                    if($attributes['SkillsIds'])
                        $criteria->addNotInCondition('id', $attributes['SkillsIds']);
                }
            }
            
            $criteria->limit = 10;
            $skills = Skills::model()->findAll($criteria);
            $res = '';
            foreach ($skills  as $skill)
            {
                $res .= "<li data-id='$skill->id'><a href='#'>$skill->name</a></li>";
            }
            if($res=='')
                $res = '<li><a href="#">Результатов нет</a></li>';
            echo $res;
	}
        
	public function actionSaveChange()
	{
            if(!$_POST['LessonGroupCriteria'])
                return false;
            foreach($_POST['LessonGroupCriteria'] as $id_criteria => $attributes)
            {
                $model= LessonGroupCriteria::model()->findByPk($id_criteria);
                if(!$model)
                    die('Не существует такой критерии !');
                $model->attributes = $attributes;
                if($model->save())
                {
                    if($attributes['SkillsIds'])
                    {
                        // сначала удаляем все удаленные скиллы
                        $criteria = new CDbCriteria;
                        $criteria->condition = '`id_lesson_criteria` = :id_lesson_criteria';
                        $criteria->params['id_lesson_criteria'] = $id_criteria;
                        $criteria->addNotInCondition('id_skill', $attributes['SkillsIds']);
                        LessonGroupCriteriaAndSkills::model()->deleteAll($criteria);
                        $criteriaAndSkills = new LessonGroupCriteriaAndSkills();
                        // добавляем скиллы
                        foreach($attributes['SkillsIds'] as $skill_id)
                        {
                            if(!LessonGroupCriteriaAndSkills::model()->findByAttributes(array('id_lesson_criteria'=>$id_criteria, 'id_skill'=>$skill_id)))
                            {
                                $criteriaAndSkills->id_lesson_criteria = $id_criteria;
                                $criteriaAndSkills->id_skill = $skill_id;  
                                $criteriaAndSkills->save();
                                $criteriaAndSkills->id = false;
                                $criteriaAndSkills->isNewRecord = true;
                            }
                        }
                    } else {
                        // если скиллы пусты значит удаляем все скиллы
                        LessonGroupCriteriaAndSkills::model()->deleteAllByAttributes(array('id_lesson_criteria'=>$id_criteria));
                    }
                    echo 1;
                }
            }
	}

	public function loadModel($id)
	{
		$model=LessonGroupCriteria::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lesson-group-criteria-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
