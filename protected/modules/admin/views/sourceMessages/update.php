<?php
/* @var $this SourceMessagesController */
/* @var $model SourceMessages */

$this->breadcrumbs=array(
	'Source Messages'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SourceMessages', 'url'=>array('index')),
	array('label'=>'Create SourceMessages', 'url'=>array('create')),
	array('label'=>'View SourceMessages', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SourceMessages', 'url'=>array('admin')),
);
?>

<h1>Update SourceMessages <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>