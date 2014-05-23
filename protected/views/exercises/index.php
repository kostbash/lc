<?php
/* @var $this ExercisesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Exercises',
);

$this->menu=array(
	array('label'=>'Create Exercises', 'url'=>array('create')),
	array('label'=>'Manage Exercises', 'url'=>array('admin')),
);
?>

<h1>Exercises</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
