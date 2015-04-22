<?php
/* @var $this SkillsgroupsController */
/* @var $model SkillsGroups */

$this->breadcrumbs=array(
	'Skills Groups'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SkillsGroups', 'url'=>array('index')),
	array('label'=>'Create SkillsGroups', 'url'=>array('create')),
	array('label'=>'Update SkillsGroups', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SkillsGroups', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SkillsGroups', 'url'=>array('admin')),
);
?>

<h1>View SkillsGroups #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_course',
		'name',
		'date_create',
		'creator_id',
	),
)); ?>
