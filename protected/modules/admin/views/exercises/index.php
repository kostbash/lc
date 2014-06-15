<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/ckeditor/ckeditor.js");
Yii::app()->clientScript->registerScript('exercises', "
    $('.zgrid .update-record').live('change', function(){
        current = $(this);
            
        $.ajax({
             url: '" . Yii::app()->createUrl('admin/exercises/updatebyajax') . "',
             type:'POST',
             data: current.closest('tr').find('input, textarea, select').serialize(),
             success: function(result) { 
                if(result==1)
                    current.closest('.zgrid').yiiGridView('update');
                else if(result!='')
                    alert(result);
              }
         });
    });
    
    $('.zgrid .new-record').live('change', function(){
        current = $(this);
        $.ajax({
             url: '" . Yii::app()->createUrl('admin/exercises/create', array('id_course'=>$id_course)) . "',
             type:'POST',
             data: current.serialize(),
             success: function(result) { 
                if(result==1)
                    current.closest('.zgrid').yiiGridView('update');
                else if(result!='')
                    alert(result);
              }
         });
    });
    
        $('.zgrid .mydrop input').live('keyup', function(){
            current = $(this);
            $.ajax({
                'url':'" . Yii::app()->createUrl('admin/exercises/skillsbyajax') . "/id_exercise/'+current.data('id'),
                'type':'POST',
                'data': { term: current.val()},
                'success': function(result) { 
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
        });

        $('.zgrid .mydrop .dropdown-toggle').live('click', function(){
            current = $(this);
            $.ajax({
                'url':'" . Yii::app()->createUrl('admin/exercises/skillsbyajax') . "/id_exercise/'+current.parent().siblings('input').data('id'),
                'type':'POST',
                'success': function(result) { 
                    if(result!='') {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result);
                    }
                }
            });
            
        });

        $('.zgrid .mydrop .dropdown-menu li').live('click', function(){
            current = $(this);
            input = current.closest('.input-group-btn').siblings('input');
            $.ajax({
                'url':'" . Yii::app()->createUrl('admin/exerciseAndSkills/create') . "/id_exercise/'+input.data('id'),
                'type':'POST',
                'data':{ 'id_skill': current.data('id') },
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
        
        $('.zgrid .inputs-mini .close').live('click', function(){
            if(!confirm('Вы действительно хотите удалить данное умение ?'))
                return false;
            current = $(this);
            id_skill = current.closest('.input-mini-container').data('id');
            id_exercise = current.closest('.inputs-mini').find('input').data('id');
            $.ajax({
                'url':'" . Yii::app()->createUrl('admin/exerciseAndSkills/delete') . "',
                'type':'POST',
                'data':{ id_skill: id_skill, id_exercise: id_exercise },
                'success': function(result) { 
                    if(result==1) {
                        $(current).parents('.zgrid').yiiGridView('update');
                    }
                    else if(result!='')
                        alert(result);
                }
            });
            return false;
        });
        
        $('.zgrid .button-column .delete').live('click', function(){
            current = $(this);
            if(current.closest('tr').data('candelete')==1)
                if(!confirm('Вы действительно хотите удалить данное задание ?'))
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
        
        $('.zgrid .new-record').closest('tr').find('.select-on-check').remove();
        
        $('.choose').click(function(){
            $(this).closest('form').submit();
            return false;
        });
        
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
                alert('Задание не может быть пустым !');
                return false;
            }
            if(editing.val()!=data)
            {
                editing.val(data);

                $.ajax({
                     url: '" . Yii::app()->createUrl('admin/exercises/updatebyajax') . "',
                     type:'POST',
                     data: editing.closest('tr').find('input, textarea').serialize(),
                     success: function(result) { 
                        if(result==1)
                            current.closest('.zgrid').yiiGridView('update');
                        else if(result!='')
                            alert(result);
                      }
                 });
            }
            $('#htmlEditor').modal('hide');
        });
        
    ");
?>

<div class="page-header clearfix">
    <h2>Задания <?php if($course) echo " курса \"$course->name\""; ?></h2>
</div>

<div class="modal fade" id="htmlEditor" tabindex="-1" role="dialog" aria-labelledby="htmlEditorLabel" aria-hidden="true">
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

<?php if (!$id) : ?>
    <div class="search-form">
        <?php
        $this->renderPartial('_search', array(
            'model' => $model,
            'group' => $group,
        ));
        ?>
    </div><!-- search-form -->
<?php endif; ?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'exercises-form',
    'enableAjaxValidation' => false,
        ));
?>
<script type="text/javascript" src="/js/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="/js/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="/js/swfupload/handlers.js"></script>
<script type="text/javascript" src="/js/uploader.js"></script>
<script src="/js/jquery.html5_upload.js" type="text/javascript"></script>
<?php
$search = $model->search();
//$count = $model->limit ? $search->itemCount - 1 : $search->totalItemCount;
$count = $search->totalItemCount;
$file = CHtml::fileField('ImportFile', '', array('onchange' => '$(this).hide();', 'style' => 'width:100%;'));
$this->widget('ZGridView', array(
    'id' => 'exercises-grid',
    'rowHtmlOptionsExpression' => 'array("data-candelete"=>"$data->canDelete")',
    'selectableRows' => 2,
    'ajaxType'=>'POST',
    'summaryText' => "Всего $count заданий <div class='pull-right'>".CHtml::link('<i class="glyphicon glyphicon-remove"></i>Удалить выделенные', '#', array('class'=>'btn btn-danger btn-icon exercises-remove', 'style'=>'float:left;'))." <button onclick='$(this).hide();$(\"#import-input\").show();return false;' id='import-button' class='btn btn-success' style='margin-left:10px;'>Импорт</button><div id='import-input' style='display:none;float:left; line-height:35px; margin-left:10px;'>$file</div></div>",
    'dataProvider' => $search,
    'afterAjaxUpdate' => 'uploadHandle',
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'id' => 'checked',
            'value' => '$data->id',
            'htmlOptions' => array('width' => '2%'),
        ),
        'id',
        array(
            'name' => 'condition',
            'type' => 'forEditor',
            'htmlOptions' => array('width' => '40%'),
        ),
        
        array(
            'name' => 'difficulty',
            'type' => 'raw',
            'value' => 'CHtml::dropDownList("Exercises[$data->id][difficulty]", $data->difficulty, Exercises::getDataDifficulty(), array("class"=>"form-control update-record", "empty"=>"Выберите сложность"))',
            'htmlOptions' => array('width' => '10%'),
        ),
        
        array(
            'name' => 'Skills',
            'header' => 'Используемые умения',
            'type' => 'labelCourseLessonSkill',
            'htmlOptions' => array('width' => '20%'),
        ),
        array(
            'name'=>'Type.name',
            'header'=>'Тип',
            'htmlOptions' => array('width' => '10%'),
        ),
        array(
            'name'=>'Visual.name',
            'header'=>'Визуалзация',
            'htmlOptions' => array('width' => '12%'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'buttons' => array(
                'delete' => array(
                    'click' => 'false',
                ),
            ),
            'htmlOptions' => array('width' => '6%'),
        ),
    ),
));
?>
<?php
if($group)
    echo CHtml::submitButton("Выбрать", array('class' => 'btn btn-default'));

Yii::app()->clientScript->registerScript('search' . $x . $this->id, '
                        function uploadHandle()
                        {
                            var f = $("#ImportFile"),
			    up = new uploader(f.get(0), {
				prefix:"ImportFile",
				url:"' . Yii::app()->createUrl("/admin/exercises/SWFUpload", array('id_course'=>$id_course)) . '",
				autoUpload:true,
				error:function(ev){ console.log("error"); remove_veil(); $("#import-button").show();$("#import-input").hide();},
				success:function(data){$(document.body).after(data);remove_veil();$("#import-button").show();$("#import-input").hide();}
			    });
                             $(".exercises-remove").click(function(){
                                if(confirm("Вы действительно хотите удалить отмеченные задания ?")) {
                                     $.ajax({
                                         url: "'.Yii::app()->createUrl('admin/exercises/massdelete').'",
                                         type:"POST",
                                         data: $("input[name*=checked]:checked").serialize(),
                                         success: function(result) { 
                                                         $("#exercises-grid").yiiGridView("update");
                                                         if(result!=1)
                                                             alert(result);
                                          }
                                     });
                                }
                                return false;
                            });
                            
                            $(".new-record").closest("tr").find(".checkbox-column input").remove();
                        }
			$(function(){
                            	uploadHandle();
			});
		    ');
$this->endWidget();
?>

<div id="dropdown-types_of_exercises" style="width: 136px;">
    <div class="input-group-btn">
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i><p>Новое задание</p><p class="caret-cont"><b class="caret"></b></p>', "#", array('class'=>'btn btn-success btn-icon dropdown-toggle clearfix', 'id'=>'types-link', 'data-toggle'=>"dropdown", "tabindex"=>"-1")) ?>
        <ul class="dropdown-menu" role="menu">
            <?php foreach(ExercisesTypes::model()->findAll() as $type) : ?>
                <li class='type'>
                    <?php echo CHtml::link($type->name, array('/admin/exercises/create', 'id_type'=>$type->id)); ?>
                </li>
                <?php foreach($type->Visuals as $visual) : ?>
                    <li class='visual'>
                        <?php echo CHtml::link($visual->name, array('/admin/exercises/create', 'id_type'=>$type->id, 'id_visual'=>$visual->id)); ?>
                    </li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>