<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/ckeditor/ckeditor.js");
Yii::app()->clientScript->registerScript("UpdateByAjax",
    "$(function(){

            $('.for-editor-field').live('click', function() {
                current = $(this);
                hidden = current.siblings('input[type=hidden]');
                if(hidden.attr('id')!='editingQuestion')
                {
                    $('#editingQuestion').removeAttr('id');
                    hidden.attr('id', 'editingQuestion');
                    CKEDITOR.instances['editor-text'].setData(hidden.val());
                    CKEDITOR.on('instanceReady', function (ev) {
                       // Prevent drag-and-drop.
                       ev.editor.document.on('drop', function (ev) {
                          ev.data.preventDefault(true);
                       });
                    });
                }
                $('#htmlEditor').modal('show');
            });

            $('#amend').click(function() {
                data = CKEDITOR.instances['editor-text'].getData();
                editing = $('#editingQuestion');
                if(!$.trim(data))
                {
                    alert('Задание не может быть пустым !');
                    return false;
                }
                if(editing.val()!=data)
                    editing.val(data).siblings('.for-editor-field').html(data).closest('tr');
                    current = editing;
                    $.ajax({
                    'url':'".Yii::app()->createUrl("admin/skillssteps/update")."',
                    'type':'POST',
                    'data':$(current).serialize(),
                    'success': function(result) {
                                    if(result==1)
                                        $(current).parents('.zgrid').yiiGridView('update');
                                    else if(result!='')
                                        alert(result);
                     }
                });
                $('#htmlEditor').modal('hide');
            });

            $('.zgrid .new-record').live('change', function(){
                current = this;
                $.ajax({
                    'url':'".Yii::app()->createUrl("admin/skillssteps/create")."',
                    'type':'POST',
                    'data': {'Steps':{'name':$(this).val(), id_skill:". $id_skill ."}},
                    'success': function(result) {
                                    if(result==1)
                                        $(current).parents('.zgrid').yiiGridView('update');
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });

            $('.zgrid .update-record').live('change', function(){
                current = this;
                $.ajax({
                    'url':'".Yii::app()->createUrl("admin/skillssteps/update")."',
                    'type':'POST',
                    'data':$(this).serialize(),
                    'success': function(result) {
                                    if(result==1)
                                        $(current).parents('.zgrid').yiiGridView('update');
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });



            $('.delete-main-skill').live('click', function() {
                current = $(this);
                if(current.closest('tr').data('candelete') === 1)
                    if(!confirm('Вы уверены, что хотите удалить данное умение ?'))
                        return false;
                      $.ajax({
                         'url': current.attr('href'),
                         'type':'POST',
                         'success': function(result) {
                                         if(result==1)
                                             current.parents('.zgrid').yiiGridView('update');
                                         else if(result!=='')
                                             alert(result);
                          }
                     });
                return false;
            });

            $('.input-mini-container .close').live('click', function() {
                current = $(this);
                if(!confirm('Вы уверены, что хотите удалить данное умение ?'))
                    return false;
                $.ajax({
                   'url': current.attr('href'),
                   'type':'POST',
                   'success': function(result) {
                                   if(result==1)
                                       current.closest('.zgrid').yiiGridView('update');
                                   else if(result!=='')
                                       alert(result);
                    }
                });
                return false;
             });
        });"
);
?>



<div class="page-header clearfix">
    <h2>Шаги умения <?php if($skill) echo " \"$skill->name\""; ?></h2>
    <!--    <a class="btn btn-success btn-sm" href="/admin/skills/showtree/id_course/--><?//=$course->id?><!--">Посмотреть дерево умений</a>-->
    <!--    <a style="margin-right: 10px;" class="btn btn-success btn-sm" href="/admin/skillsgroups/index/id_course/--><?//=$course->id?><!--">Группы умений</a>-->
</div>

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

<div class="row">
    <div class="pull-left col-lg-7 col-md-6">
        <h3 class="head-data">Шаги</h3>
        <div class="well">

            <?php $this->widget('ZGridView', array(
                'dataProvider'=>$model->searchSteps(),
                'id'=>'knowledge-grid',
                'rowHtmlOptionsExpression' => 'array("data-candelete"=>$data->canDelete)',
                'columns'=>array(

                    array(
                        'name'=>'name',
                        //'header'=>'N1',
                        'type'=>'textAreaSkill',
                        'htmlOptions'=>array('style'=>'width: 30%'),
                    ),
                    array(
                        'name'=>'condition',
                        'header'=>'Html текст',
                        'type'=>'forEditor',
                        'htmlOptions'=>array('style'=>'width: 30%'),
                        'visibleCell'=>'!$data->isNewRecord',
                    ),

                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{delete}',
                        'buttons'=>array(
                            'delete'=>array(
                                'visible'=>'!$data->isNewRecord',
                                'click'=>'false',
                                'options'=>array('class'=>'delete-main-skill'),
                            ),
                        ),

                        'htmlOptions'=>array('style'=>'width: 10%'),
                    ),
                ),

            )); ?>
        </div>
    </div>
</div>
</div>