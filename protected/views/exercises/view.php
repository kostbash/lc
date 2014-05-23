<?php
/* @var $this ExercisesController */
/* @var $model Exercises */

$this->breadcrumbs=array(
	'Exercises'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Exercises', 'url'=>array('index')),
	array('label'=>'Create Exercises', 'url'=>array('create')),
	array('label'=>'Update Exercises', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Exercises', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Exercises', 'url'=>array('admin')),
);
?>

<h1>View Exercises #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'question',
		'correct_answer',
		'difficulty',
	),
)); ?>
