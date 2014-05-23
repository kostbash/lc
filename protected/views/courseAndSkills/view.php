<?php
/* @var $this CourseAndSkillsController */
/* @var $model CourseAndSkills */

$this->breadcrumbs=array(
	'Course And Skills'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CourseAndSkills', 'url'=>array('index')),
	array('label'=>'Create CourseAndSkills', 'url'=>array('create')),
	array('label'=>'Update CourseAndSkills', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CourseAndSkills', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CourseAndSkills', 'url'=>array('admin')),
);
?>

<h1>View CourseAndSkills #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_course',
		'id_skill',
	),
)); ?>
