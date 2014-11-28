<div class="page-header clearfix">
    <h2>Тексты</h2>
</div>

<script>
    $(function(){
        $('#source-messages-grid .save-row').live('click', function(){
           current = $(this);

           $.ajax({
                url: current.attr('href'),
                type:'POST',
                data: current.closest('tr').find('textarea, input, select').serialize(),
                dataType: 'json',
                success: function(result) { 
                    if(result.success) {
                        current.hide();
                    }
                    else {
                        alert(result.errors);
                    }
                }
            });
            return false;
        });
        
        $('#source-messages-grid .update-record').live('change', function(){
           current = $(this);
           current.closest('tr').find('.save-row').show();
        });
    });
</script>

<?php $this->widget('ZGridView', array(
	'id'=>'source-messages-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
            array(
                'name'=>'id',
                'htmlOptions'=>array('style'=>'width: 10%'),
            ),
            array(
                'name'=>'page_name',
                'htmlOptions'=>array('style'=>'width: 20%'),
            ),
            array(
                'name'=>'type',
                'htmlOptions'=>array('style'=>'width: 15%'),
            ),
            array(
                'name'=>'message',
                'type'=>'textArea',
                'htmlOptions'=>array('style'=>'width: 50%'),
            ),
            array(
                    'class'=>'CButtonColumn',
                    'template'=>'{save}',
                    'buttons'=>array(
                        'save'=>array(
                            'label'=>'<i class="glyphicon glyphicon-floppy-disk"></i>',
                            'url'=>'Yii::app()->createUrl("/admin/sourceMessages/update", array("id"=>$data->id))',
                            'options'=>array('class'=>'save-row', 'title'=>'Сохранить изменения'),
                        ),
                    ),
                    'htmlOptions'=>array('style'=>'width: 5%'),
            ),
	),
)); ?>
