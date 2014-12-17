<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/ckeditor/ckeditor.js");
?>

<script>
    $(function(){
        $('.for-editor-field').live('click', function() {
            current = $(this);
            hidden = current.siblings('input[type=hidden]');
            if(hidden.attr('id')!='editingQuestion')
            {
                $('#editingQuestion').removeAttr('id');
                hidden.attr('id', 'editingQuestion');
                CKEDITOR.instances['editor-text'].setData(hidden.val());
            }
            $('#htmlEditor').modal('show');
        });

        $('#amend').click(function() {
            data = CKEDITOR.instances['editor-text'].getData();
            editing = $('#editingQuestion');
            if(!$.trim(data))
            {
                alert('Введите текст сообщения');
                return false;
            }
            if(editing.val()!=data)
            {
                editing.val(data);
                $.ajax({
                     url: '<?php echo Yii::app()->createUrl('admin/mailWorkpieces/updatebyajax'); ?>',
                     type:'POST',
                     data: editing.closest('tr').find('input, textarea').serialize(),
                     dataType: 'json',
                     success: function(result) { 
                        if(result.success)
                        {
                            editing.siblings('.for-editor-field').html(data);
                        }
                        else
                        {
                            alert(result.errors);
                        }
                      }
                 });
            }
            $('#htmlEditor').modal('hide');             
        });
        
        $('.update-record').live('change', function() {
            current = $(this);
            $.ajax({
                 url: '<?php echo Yii::app()->createUrl('admin/mailWorkpieces/updatebyajax'); ?>',
                 type:'POST',
                 data: current.closest('tr').find('input, textarea').serialize(),
                 dataType: 'json',
                 success: function(result) { 
                    if(result.success)
                    {
                        
                    }
                    else
                    {
                        alert(result.errors);
                    }
                  }
             });
        });
        
        $('.send-message').live('click', function() {
            current = $(this);
            $.ajax({
                 url: current.attr('href'),
                 type:'POST',
                 dataType: 'json',
                 success: function(result) { 
                    if(result.success)
                    {
                        alert(result.message);
                        current.closest('tr').remove();
                    }
                    else
                    {
                        alert(result.error);
                    }
                  }
             });
             
             return false;
        });
    });
</script>

<div class="modal fade" id="htmlEditor" role="dialog" aria-labelledby="htmlEditorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="htmlEditorLabel">Html-редактор</h4>
      </div>
      <div class="modal-body">
          <textarea id="editor-text" class="ckeditor" name="editor"></textarea>
      </div>
      <div class="modal-footer">
        <?php echo CHtml::Button('Внести изменения', array("class"=>"btn btn-primary", 'id'=>'amend')); ?>
      </div>
    </div>
  </div>
</div>


<div class="page-header clearfix">
    <h2>Лента</h2>
</div>

<div class="btn-group">
  <?php echo CHtml::link('Заготовки', array('/admin/mailWorkpieces/unsended'), array('class'=>'btn btn-default active')); ?>
  <?php echo CHtml::link('Отправленные', array('/admin/mailWorkpieces/sended'), array('class'=>'btn btn-default')); ?>
</div>

<?php
$this->widget('ZGridView', array(
    'id'=>'mail-workpieces-grid',
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name'=>'id',
            'htmlOptions'=>array('style'=>'width: 5%;'),
        ),
        array(
            'name'=>'addressee',
            'htmlOptions'=>array('style'=>'width: 10%;'),
        ),
        array(
            'header'=>'Пользователь',
            'value'=>'$data->User->username',
            'htmlOptions'=>array('style'=>'width: 10%;'),
        ),
        array(
            'name'=>'ruleName',
            'htmlOptions'=>array('style'=>'width: 16%;'),
        ),
        array(
            'name'=>'number',
            'htmlOptions'=>array('style'=>'width: 5%;'),
        ),
        array(
            'name'=>'subject',
            'type'=>'textField',
            'htmlOptions'=>array('style'=>'width: 14%;'),
        ),
        array(
            'name'=>'template',
            'type'=>'forEditor',
            'htmlOptions'=>array('style'=>'width: 28%;'),
        ),
        array(
            'name'=>'sendLink',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'width: 12%;'),
        ),
    ),
));
?>
