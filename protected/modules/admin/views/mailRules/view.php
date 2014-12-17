<?php
/* @var $this MailRulesController */
/* @var $model MailRules */

$this->breadcrumbs=array(
	'Mail Rules'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List MailRules', 'url'=>array('index')),
	array('label'=>'Create MailRules', 'url'=>array('create')),
	array('label'=>'Update MailRules', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MailRules', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MailRules', 'url'=>array('admin')),
);
?>

<h1>View MailRules #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'use_number',
		'interval',
		'passed_reg_days',
		'unactivity_days',
		'number_of_passed_lessons',
		'passed_course',
		'number_of_passed_courses',
	),
)); ?>
