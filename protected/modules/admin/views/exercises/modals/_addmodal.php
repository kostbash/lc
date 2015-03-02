<script type="text/javascript">
    $(function() {
        $("#paste-button").one("click", function() {
            var widget = $('#add-modal-widget').val();

            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/exercises/buildexpressionbuttons/'); ?>',
                data: $("#add-modal-form-" + widget).serialize(),
                type: 'POST',
                //dataType: 'json',
                complete: function(result) {

                     var area =  $('#Exercises_questions_0_text');
                    if (area.length == 0) {
                        area = $('#GeneratorsTemplates_template');

                    }
                    p_start = $('#textarea-position').val();
                    area.val(area.val().substring(0, p_start) + result.responseText + area.val().substring(p_start, area.val().length));

                    $('#add-modal-' + widget).modal('hide');
                }
            });
            return false;
        });
        
        $('#w').focus();

    });
</script>
<style>
    input:focus
    {
        background:#ccc;
    }
</style>
<!-- EDITOR MESSAGE MODAL -->
<div class="modal fade" id="add-modal-<?php echo $widget; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i> Заполните поля</h4>
            </div>

            <div class="modal-body">
                <form id="add-modal-form-<?php echo $widget; ?>" enctype="multipart/form-data">
                    <input id='add-modal-widget' type='hidden' name='widget' value='<?php echo $widget; ?>'/>
                    <input id='textarea-position' type='hidden' value='0'/>
                    <?php
                    $this->renderPartial('/exercises/modals/_' . $widget, array(), false, true);
                    //$objWidget->drawModalBody();
                    ?>
                </form>
            </div>

            <div class="modal-footer clearfix">
                <button type="button" class="btn btn-primary pull-left" id="paste-button">Вставить</button>

                <?php
                /* echo CHtml::ajaxButton('Вставить', CController::createUrl('exercises/buildexpressionbuttons/', array('widget' => $widget)), array(
                  'type' => 'POST',
                  'data' => 'js:($("#add-modal-form-' . $widget . '").serialize())',
                  'success' => "js:function(data)
                  {
                  //console.log(data);
                  //var obj = $.parseJSON(data.responseText);

                  $('#test').html(data);
                  $('#pastebutton').off('click');
                  $('#Exercises_questions_0_text').val($('#Exercises_questions_0_text').val()+data);
                  $('#add-modal-" . $widget . "').modal('hide');

                  }"
                  ), array(
                  'type' => 'submit',
                  'id' => 'pastebutton',
                  'class' => 'btn btn-primary pull-left',
                  )
                  ); */
                ?>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Отменить</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->