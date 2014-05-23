<script type="text/javascript">
    $(function(){
        $('.users-remove').click(function(){
            if(confirm('Вы действительно хотите удалить отмеченных пользователей ?'))
                $(this).closest('form').submit();
            return false;
        });
    });
</script>

<div class="page-header clearfix">
    <h2>Пользователи</h2>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
        'action'=>$this->createUrl('/admin/users/massdelete'),
)); ?>

<?php $this->widget('ZGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
        'selectableRows' => 2,
	'columns'=>array(
                array(
                    'class'=>'CCheckBoxColumn',
                    'id' => 'checked',
		    'value' => '$data->id',
                ),
		'email',
                'registration_day',
                'countPassLessons',
                'last_activity',
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{delete}',
		),
	),
)); ?>

<?php echo CHtml::link('<i class="glyphicon glyphicon-remove"></i>Удалить выделенные', '#', array('class'=>'btn btn-sm btn-danger btn-icon users-remove')); ?>

<?php $this->endWidget(); ?>


