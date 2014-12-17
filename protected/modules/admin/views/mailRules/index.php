<div class="page-header clearfix">
    <h2>Правила</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', array('mailRules/create'), array('class'=>'btn btn-primary btn-icon')); ?>
</div>
<?php $this->widget('ZGridView', array(
	'id'=>'mail-rules-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'id',
		'name',
		'use_number',
		'interval',
		array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}{delete}',
		),
	),
)); ?>