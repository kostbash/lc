<?php
/* @var $this CourseAndSkillsController */
/* @var $model CourseAndSkills */

$this->breadcrumbs=array(
	'Course And Skills'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CourseAndSkills', 'url'=>array('index')),
	array('label'=>'Manage CourseAndSkills', 'url'=>array('admin')),
);
?>

<h1>Create CourseAndSkills</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>