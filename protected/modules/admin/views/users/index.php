<script type="text/javascript">
    $(function(){
        $('.users-remove').click(function(){
            if(confirm('Вы действительно хотите удалить отмеченных пользователей ?'))
                $(this).closest('form').submit();
            return false;
        });
        
        $('.reset-pass').live('click',function(){
            if(confirm('Вы действительно хотите сбросить пароль пользователю ?'))
            {
                current = $(this);
                $.ajax({
                    url: current.attr('href'),
                    type:'POST',
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success) {
                            alert('Пароль успешно сброшен!');
                        }
                    }
                });
            }
            return false;
        });
        
        $('.subscribe').live('click',function(){
            if(confirm('Вы действительно подписать пользователя на рассылку ?'))
            {
                current = $(this);
                $.ajax({
                    url: current.attr('href'),
                    type:'POST',
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success) {
                            alert('Пользователь успешно подписан');
                            updateGrid();
                        }
                    }
                });
            }
            return false;
        });
        
        $('.unsubscribe').live('click',function(){
            if(confirm('Вы действительно отписать пользователя от рассылки ?'))
            {
                current = $(this);
                $.ajax({
                    url: current.attr('href'),
                    type:'POST',
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success) {
                            alert('Пользователь успешно отписан');
                            updateGrid();
                        }
                    }
                });
            }
            return false;
        });
    });
    
    function updateGrid()
    {
       $('#users-grid').yiiGridView('update', { data: $('.search-form form').serialize()+'&filter=1' });
    }
</script>

<div class="page-header clearfix">
    <h2>Пользователи</h2>
</div>

<?php
$this->renderPartial('_search', array(
    'model' => $model,
));
?>
    
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
        'action'=>$this->createUrl('/admin/users/massdelete'),
)); ?>

<?php $this->widget('ZGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
        'selectableRows' => 2,
        'summaryText'=>"Всего найдено записей: {count}",
        'ajaxType'=>'POST',
	'columns'=>array(
                array(
                    'class'=>'CCheckBoxColumn',
                    'id' => 'checked',
		    'value' => '$data->id',
                ),
                'username',
		'email',
                'rusRoleName',
                'registration_day',
                array(
                    'name'=>'countPassLessons',
                    'value'=>'CHtml::link($data->countPassLessons, array("/admin/users/logs", "id"=>$data->id), array("target"=>"_blank"))',
                    'type'=>'raw',
                ),
                'last_activity',
                'myParent',
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{subscribe}{unsubscribe}{reset}{delete}',
                        'buttons' => array(
                            'subscribe' => array(
                                'label'=>'',
                                'url'=>'Yii::app()->createUrl("/admin/users/changesubscribe", array("id"=>"$data->id"))',
                                'options'=>array('class'=>'subscribe', 'title'=>'Подписать'),
                                'visible'=>'!$data->send_mailing',
                            ),
                            'unsubscribe' => array(
                                'label'=>'',
                                'url'=>'Yii::app()->createUrl("/admin/users/changesubscribe", array("id"=>"$data->id"))',
                                'options'=>array('class'=>'unsubscribe', 'title'=>'Отписать'),
                                'visible'=>'$data->send_mailing',
                            ),
                            'reset' => array(
                                'label'=>'',
                                'url'=>'Yii::app()->createUrl("/admin/users/resetpassword", array("id"=>"$data->id"))',
                                'options'=>array('class'=>'reset-pass', 'title'=>'Сбросить пароль'),
                            ),
                        ),
		),
	),
)); ?>

<?php echo CHtml::link('<i class="glyphicon glyphicon-remove"></i>Удалить выделенные', '#', array('class'=>'btn btn-sm btn-danger btn-icon users-remove')); ?>

<?php $this->endWidget(); ?>


