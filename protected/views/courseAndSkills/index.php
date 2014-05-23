<?php
/* @var $this CourseAndSkillsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Course And Skills',
);

$this->menu=array(
	array('label'=>'Create CourseAndSkills', 'url'=>array('create')),
	array('label'=>'Manage CourseAndSkills', 'url'=>array('admin')),
);
?>

<h1>Course And Skills</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
