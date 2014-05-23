<div class="widget page-header clearfix" style="padding-top: 10px; background: white;">
    <h2><?php echo $model->name; ?></h2>
    <?php 
    $act = $model->hasUserCourse ? 'Продолжить' : 'Начать';
    echo CHtml::link("$act курс<i class='glyphicon glyphicon-log-in'></i>", array('courses/add', 'id'=>$model->id), array('class'=>'btn btn-success btn-icon-right')); ?>
</div>
<div class="widget" style="padding-top: 10px">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
	),
)); ?>
</div>