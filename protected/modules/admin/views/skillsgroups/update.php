<?php
/* @var $this SkillsgroupsController */
/* @var $model SkillsGroups */

$this->breadcrumbs=array(
	'Skills Groups'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SkillsGroups', 'url'=>array('index')),
	array('label'=>'Create SkillsGroups', 'url'=>array('create')),
	array('label'=>'View SkillsGroups', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SkillsGroups', 'url'=>array('admin')),
);
?>

<h1>Update SkillsGroups <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>