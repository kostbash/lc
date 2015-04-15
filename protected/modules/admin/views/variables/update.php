<?php
/* @var $this VariablesController */
/* @var $model Variables */

$this->breadcrumbs=array(
	'Variables'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Variables', 'url'=>array('index')),
	array('label'=>'Create Variables', 'url'=>array('create')),
	array('label'=>'View Variables', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Variables', 'url'=>array('admin')),
);
?>

<h1>Update Variables <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>