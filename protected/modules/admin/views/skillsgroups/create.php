<?php
/* @var $this SkillsgroupsController */
/* @var $model SkillsGroups */

$this->breadcrumbs=array(
	'Skills Groups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SkillsGroups', 'url'=>array('index')),
	array('label'=>'Manage SkillsGroups', 'url'=>array('admin')),
);
?>

<h1>Create SkillsGroups</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>