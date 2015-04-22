<?php
/* @var $this SkillsStepsController */
/* @var $model SkillsSteps */

$this->breadcrumbs=array(
	'Skills Steps'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SkillsSteps', 'url'=>array('index')),
	array('label'=>'Create SkillsSteps', 'url'=>array('create')),
	array('label'=>'Update SkillsSteps', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SkillsSteps', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SkillsSteps', 'url'=>array('admin')),
);
?>

<h1>View SkillsSteps #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_course',
		'name',
		'condition',
	),
)); ?>
