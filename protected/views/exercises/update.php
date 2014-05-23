<?php
/* @var $this ExercisesController */
/* @var $model Exercises */

$this->breadcrumbs=array(
	'Exercises'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Exercises', 'url'=>array('index')),
	array('label'=>'Create Exercises', 'url'=>array('create')),
	array('label'=>'View Exercises', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Exercises', 'url'=>array('admin')),
);
?>

<h1>Update Exercises <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>