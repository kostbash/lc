<div class="page-header clearfix">
    <h2>Мои дети</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить ребенка', array('/admin/childrenofparent/create'), array('class'=>'btn btn-primary btn-icon')); ?>
</div>
<?php $this->widget('ZgridView', array(
	'id'=>'children-of-parent-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'child_name',
		'child_surname',
                'newNotifications:raw',
		'statusText:raw',
		array(
			'class'=>'CButtonColumn',
                        'buttons' => array(
                            'view'=>array(
                                'visible'=>'$data->status==1',
                            ),
                        ),
		),
	),
)); ?>
