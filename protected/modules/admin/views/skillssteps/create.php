<?php
/* @var $this SkillsStepsController */
/* @var $model SkillsSteps */

$this->breadcrumbs=array(
	'Skills Steps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SkillsSteps', 'url'=>array('index')),
	array('label'=>'Manage SkillsSteps', 'url'=>array('admin')),
);
?>

<h1>Create SkillsSteps</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>