<div class="page-header clearfix">
    <h2>Умения</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', array('courses/create'), array('class'=>'btn btn-primary')); ?>
</div>
<div class="row">
<div class="pull-left col-lg-6 col-md-6">
<h3 class="head-data">Навыки</h3>
<div class="well">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'knowledge-grid',
	'dataProvider'=>$model->searchSkills(),
	'columns'=>array(
		'name',
                'underSkills',
                'countExercises',
		array(
			'class'=>'CButtonColumn',
                        'buttons'=>array('template'=>'{delete}'),
		),
	),
        'itemsCssClass'=>'table table-hover',
)); ?>
</div>
</div>

<div class="pull-right col-lg-6 col-md-6">
<h3 class="head-data">Знания</h3>
<div class="well">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'skills-grid',
	'dataProvider'=>$model->searchKnowledge(),
	'columns'=>array(
		'name',
                'underSkills',
                'countExercises',
	),
        'itemsCssClass'=>'table table-hover',
)); ?>
</div>
</div>
</div>