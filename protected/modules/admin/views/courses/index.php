<div class="page-header clearfix">
    <h2>Курсы</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', array('courses/create'), array('class'=>'btn btn-primary btn-icon')); ?>
</div>
<?php $this->widget('ZGridView', array(
	'id'=>'courses-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
                'countLessons',
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{update}{delete}',
		),
	),
        'itemsCssClass'=>'table table-hover',
)); ?>
