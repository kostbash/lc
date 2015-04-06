<?php
Yii::beginProfile('index');
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
                    'url':'".Yii::app()->createUrl("admin/skills/update")."',
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
                    'url':'".Yii::app()->createUrl("admin/skills/create")."',
                    'type':'POST',
                    'data': {'Skills':{'name':$(this).val(), 'type':$(this).data('type'), id_course:". $id_course ."}},
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
                    'url':'".Yii::app()->createUrl("admin/skills/update")."',
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

            $('.mydrop input').live('keyup', function(){
                current = $(this);
                $.ajax({
                    'url':'".Yii::app()->createUrl('admin/skills/skillsbyidajax')."',
                    'type':'POST',
                    'data': { id: current.data('id'), term: current.val() },
                    'success': function(result) { 
                            current.siblings('.input-group-btn').children('.dropdown-menu').children('li').remove();
                            current.siblings('.input-group-btn').children('.dropdown-menu').append(result);
                            current.siblings('.input-group-btn').addClass('open');
                    }
                });
            });
            
            $('.mydrop .dropdown-toggle').live('click', function(){
                current = $(this);
                    $.ajax({
                        'url':'".Yii::app()->createUrl('admin/skills/skillsbyidajax')."',
                        'type':'POST',
                        'data': {id: current.parents('.mydrop').children('input').data('id')},
                        'success': function(result) { 
                            if(result!='')
                                current.siblings('.dropdown-menu').children('li').remove();
                                current.siblings('.dropdown-menu').append(result);
                        }
                    });
            });
            
            $('.mydrop .dropdown-menu li').live('click', function(){
                current = $(this);
                input = current.parents('.input-group-btn').siblings('input');
                $.ajax({
                    'url':'".Yii::app()->createUrl('admin/Relationskills/create')."',
                    'type':'POST',
                    'data':{ 'id_main': input.data('id'), 'id': current.data('id') },
                    'success': function(result) { 
                        if(result==1) {
                            $(current).parents('.zgrid').yiiGridView('update');
                        }
                        else if(result!='')
                            alert(result);
                    }
                });
                current.parents('.input-group-btn').removeClass('open');
                return false;
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
    <h2>Умения<?php if($course) echo " курса \"$course->name\""; ?></h2>
    <a class="btn btn-success btn-sm" href="http://cursys/admin/skills/showtree/id_course/<?=$course->id?>">Посмотреть дерево умений</a>
</div>
<div class="row">
<div class="pull-left col-lg-6 col-md-6">
<h3 class="head-data">Навыки</h3>
<div class="well">
<?php Yii::beginProfile('index3'); $this->widget('ZGridView', array(
	'id'=>'knowledge-grid',
	'dataProvider'=>$model->searchSkills(),
        'rowHtmlOptionsExpression' => 'array("data-candelete"=>$data->canDelete)',
	'columns'=>array(
            array(
                'name'=>'name',
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
                'name'=>'UnderSkills',
                'type'=>'labelSkill',
                'htmlOptions'=>array('style'=>'width: 30%'),
                'visibleCell'=>'!$data->isNewRecord',
            ),
            array(
                'name'=>'countExercises',
                'htmlOptions'=>array('style'=>'width: 23%'),
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
));Yii::endProfile('index3'); ?>
</div>
</div>

<div class="pull-right col-lg-6 col-md-6">
<h3 class="head-data">Знания</h3>
<div class="well">
<?php Yii::beginProfile('index2'); $this->widget('ZGridView', array(
	'id'=>'skills-grid',
	'dataProvider'=>$model->searchKnowledge(),
        'rowHtmlOptionsExpression' => 'array("data-candelete"=>$data->canDelete)',
	'columns'=>array(
            array(
                'name'=>'name',
                'type'=>'textAreaSkill',
                'htmlOptions'=>array('style'=>'width: 30%'),
            ),
            array(
                'name'=>'UnderSkills',
                'header'=>'Требуемые знания',
                'type'=>'labelSkill',
                'htmlOptions'=>array('style'=>'width: 30%'),
                'visibleCell'=>'!$data->isNewRecord',
            ),
            array(
                'name'=>'countExercises',
                'htmlOptions'=>array('style'=>'width: 23%'),
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
)); Yii::endProfile('index2'); Yii::endProfile('index'); ?>
</div>
</div>
</div>