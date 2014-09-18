<?php

class CourseParamsController extends Controller
{
	public $layout='/layouts/main';
        
        
	public function filters()
	{
            return array(
                    'accessControl', 
                    'postOnly + deleteSubject',
                    'postOnly + deleteClass',
            );
	}

	public function accessRules()
	{
            return array(
                    array('allow',
                            'actions'=>array('index', 'createSubject', 'updateSubject', 'deleteSubject', 'changeOrderSubject', 'createClass', 'updateClass', 'deleteClass'),
                            'roles'=>array('admin'),
                    ),
                    array('deny',
                            'users'=>array('*'),
                    ),
            );
	}
        
        public function actionIndex(){
            $subjects=new CourseSubjects('search');
            $subjects->unsetAttributes();
            if(isset($_GET['CourseSubjects']))
                    $subjects->attributes=$_GET['CourseSubjects'];
            
            $classes=new CourseClasses('search');
            $classes->unsetAttributes();
            if(isset($_GET['CourseClasses']))
                    $subjects->attributes=$_GET['CourseClasses'];
            
            $this->render('index',array(
                'subjects'=>$subjects,
                'classes'=>$classes,
            ));
        }
        
        public function actionCreateSubject()
        {
            $result = array('success'=>0);
            if($_POST['CourseSubjects'])
            {
                $model = new CourseSubjects;
                $model->attributes = $_POST['CourseSubjects'];
                $model->order = CourseSubjects::maxValueOrder();
                if($model->save())
                {
                    $result['success'] = 1;
                }
                else
                {
                    $result['errors'] = print_r($model->errors, true);
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionUpdateSubject()
        {
            $result = array('success'=>0);
            if($_POST['CourseSubjects'])
            {
                foreach($_POST['CourseSubjects'] as $id => $attributes)
                {
                    $model = CourseSubjects::model()->findByPk($id);
                    if($model)
                    {
                        $model->attributes = $attributes;
                        if($model->save())
                        {
                            $result['success'] = 1;
                        }
                        else
                        {
                            $result['errors'] = print_r($model->errors, true);
                        }
                    }
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionDeleteSubject($id)
        {
            $result = array('success'=>0);
            $model = CourseSubjects::model()->findByPk($id);
            if($model)
            {
                if($model->Courses)
                {
                    $msg .= "Невозможно удалить предмет. Используется в курсах: \n";
                    foreach($model->Courses as $course)
                        $msg .= $course->name."\n";
                    $result['errors'] = $msg;
                }
                else
                {
                    if($model->delete())
                    {
                        $result['success'] = 1;
                    }
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionChangeOrderSubject()
        {
            $result = array('success'=>0);
            if($_POST['id_current'] && $_POST['id_sibling'])
            {
                $current = CourseSubjects::model()->findByPk($_POST['id_current']);
                $sibling = CourseSubjects::model()->findByPk($_POST['id_sibling']);
                if($current && $sibling)
                {
                    $var = $current->order;
                    $current->order = $sibling->order;
                    $sibling->order = $var;
                    if($current->save() && $sibling->save())
                    {
                        $result['success'] = 1;
                    }
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionCreateClass()
        {
            $result = array('success'=>0);
            if($_POST['CourseClasses'])
            {
                $model = new CourseClasses;
                $model->attributes = $_POST['CourseClasses'];
                if($model->save())
                {
                    $result['success'] = 1;
                }
                else
                {
                    $result['errors'] = print_r($model->errors, true);
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionUpdateClass()
        {
            $result = array('success'=>0);
            if($_POST['CourseClasses'])
            {
                foreach($_POST['CourseClasses'] as $id => $attributes)
                {
                    $model = CourseClasses::model()->findByPk($id);
                    if($model)
                    {
                        $model->attributes = $attributes;
                        if($model->save())
                        {
                            $result['success'] = 1;
                        }
                        else
                        {
                            $result['errors'] = print_r($model->errors, true);
                        }
                    }
                }
            }
            echo CJSON::encode($result);
        }
        
        public function actionDeleteClass($id)
        {
            $result = array('success'=>0);
            $model = CourseClasses::model()->findByPk($id);
            if($model)
            {
                if($model->Courses)
                {
                    $msg .= "Невозможно удалить предмет. Используется в курсах: \n";
                    foreach($model->Courses as $course)
                        $msg .= $course->name."\n";
                    $result['errors'] = $msg;
                }
                else
                {
                    if($model->delete())
                    {
                        $result['success'] = 1;
                    }
                }
            }
            echo CJSON::encode($result);
        }
}
