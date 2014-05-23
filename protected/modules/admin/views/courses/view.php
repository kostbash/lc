<div class="page-header clearfix">
    <h2><?php echo $model->name; ?></h2>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
	),
)); ?>
adfhlk;