<?php
/* @var $this SkillsStepsController */
/* @var $model SkillsSteps */

$this->breadcrumbs=array(
	'Skills Steps'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SkillsSteps', 'url'=>array('index')),
	array('label'=>'Create SkillsSteps', 'url'=>array('create')),
	array('label'=>'View SkillsSteps', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SkillsSteps', 'url'=>array('admin')),
);
?>

<h1>Update SkillsSteps <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>