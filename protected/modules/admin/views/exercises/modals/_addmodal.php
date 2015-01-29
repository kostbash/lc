
<script type="text/javascript">
    $(function() {
        $("#paste-button").one("click", function() {
        //$('#paste-button').live('click', function() {

            var widget = $('#add-modal-widget').val();

            //console.log($('#add-modal-form-' + widget));
           // console.log("form=" + $('#add-modal-form-inp').serialize());

            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/exercises/buildexpressionbuttons/'); ?>',
                data: $("#add-modal-form-" + widget).serialize(),
                type: 'POST',
                //dataType: 'json',
                complete: function(result) {

                    $('#Exercises_questions_0_text').val($('#Exercises_questions_0_text').val() + result.responseText);
                    $('#add-modal-' + widget).modal('hide');
                }
            });
            return false;
        });
    });
</script>


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
                    <?php
                    $objWidget->drawModalBody();
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
                );*/
                ?>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Отменить</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->