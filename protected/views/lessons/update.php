<?php
/* @var $this LessonsController */
/* @var $model Lessons */

$this->breadcrumbs=array(
	'Lessons'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Lessons', 'url'=>array('index')),
	array('label'=>'Create Lessons', 'url'=>array('create')),
	array('label'=>'View Lessons', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Lessons', 'url'=>array('admin')),
);
?>

<h1>Update Lessons <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>