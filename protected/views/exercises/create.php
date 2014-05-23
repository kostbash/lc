<?php
/* @var $this ExercisesController */
/* @var $model Exercises */

$this->breadcrumbs=array(
	'Exercises'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Exercises', 'url'=>array('index')),
	array('label'=>'Manage Exercises', 'url'=>array('admin')),
);
?>

<h1>Create Exercises</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>