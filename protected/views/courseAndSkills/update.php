<?php
/* @var $this CourseAndSkillsController */
/* @var $model CourseAndSkills */

$this->breadcrumbs=array(
	'Course And Skills'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CourseAndSkills', 'url'=>array('index')),
	array('label'=>'Create CourseAndSkills', 'url'=>array('create')),
	array('label'=>'View CourseAndSkills', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CourseAndSkills', 'url'=>array('admin')),
);
?>

<h1>Update CourseAndSkills <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>