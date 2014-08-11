<div class="page-header clearfix">
    <h2>Мои ученики</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить ученика', array('/admin/studentsofteacher/create'), array('class'=>'btn btn-primary btn-icon')); ?>
</div>
<?php $this->widget('ZgridView', array(
	'id'=>'students-of-teacher-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'student_name',
		'student_surname',
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
