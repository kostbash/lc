<?php

class DealsController extends Controller
{
	public $layout='//layouts/column2';
        
        
	public function filters()
	{
		return array(
			'accessControl', 
			'postOnly + delete',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index'),
				'roles'=>array('student'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
        
	public function actionIndex()
	{
            $newParent = Children::newParents();
            $newTeachers = StudentsOfTeacher::newTeachers();

            $this->render('index',array(
                'newParent'=>$newParent,
                'newTeachers'=>$newTeachers,
            ));
	}
}
