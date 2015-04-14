<?php
/* @var $this VariablesController */
/* @var $model Variables */

$this->breadcrumbs=array(
	'Variables'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Variables', 'url'=>array('index')),
	array('label'=>'Create Variables', 'url'=>array('create')),
	array('label'=>'Update Variables', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Variables', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Variables', 'url'=>array('admin')),
);
?>

<h1>View Variables #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
		'id_course',
		'default_value',
	),
)); ?>
