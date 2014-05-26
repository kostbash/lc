<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/ckeditor/ckeditor.js");
Yii::app()->clientScript->registerScript("skills-grid",
        "$(function(){

            $('#skills-grid .update-record').live('change', function(){
                current = this;
                
                $.ajax({
                    url:'".Yii::app()->createUrl("admin/groupofexercises/updateByAjax")."',
                    type:'POST',
                    data:$(this).serialize(),
                    dataType: 'json'
                });
            });
            
            $('.type-exercise tbody tr').find('textarea, input:not([name=term]), select').live('change', function(){
                $(this).closest('tr').find('.save-row').show();
            });
            
            $('.type-exercise tbody tr .save-row').live('click', function(){
               current = $(this);
               if(current.closest('tr').data('cansave') != 1)
                    if(!confirm('Создать локальное задание на основании измененных данных ?'))
                        return false;

               $.ajax({
                    'url': current.attr('href'),
                    'type':'POST',
                    'data': current.closest('tr').find('textarea, input, select').serialize(),
                    'success': function(result) { 
                        if(result==1) {
                             current.parents('.zgrid').yiiGridView('update');
                        }
                        else if(result!='')
                            alert(result);
                    }
                });
                return false;
            });
            
            $('.type-exercise .inputs-mini .close').live('click', function(){
               if(confirm('Вы уверены, что хотите удалить данное умение ?'))
               {
                    $(this).closest('tr').find('.save-row').show();
                    $(this).closest('.input-mini-container').remove();
               }
               return false;
            });
            
            $('.type-exercise .mydrop input').live('keyup', function(){
                current = $(this);
                $.ajax({
                    url: '".Yii::app()->createUrl('admin/exercises/skillsnotidsajax')."',
                    type:'POST',
                    data: current.closest('.inputs-mini').find('input').serialize(),
                    success: function(result) {
                            current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                            current.siblings('.input-group-btn').find('.dropdown-menu').append(result);
                            current.siblings('.input-group-btn').addClass('open');
                    }
                });
            });
            
            $('.type-exercise .mydrop input, .type-test .mydrop input').live('change', function(){
                $(this).val('');
            });

            $('.type-exercise .mydrop .dropdown-toggle').live('click', function() {
                current = $(this);
                $.ajax({
                    url: '".Yii::app()->createUrl('admin/exercises/skillsnotidsajax')."',
                    type: 'POST',
                    data: current.closest('.inputs-mini').find('input').serialize(), 
                    success: function(result) { 
                        if(result!='') {
                            current.siblings('.dropdown-menu').find('li').remove();
                            current.siblings('.dropdown-menu').append(result);
                        }
                    }
                });

            });

            $('.type-exercise .mydrop .dropdown-menu li').live('click', function(){
                current = $(this);
                dataId = current.data('id');
                if(dataId)
                {
                    idExercise = current.closest('.mydrop').find('input').data('id');
                    nameSkill = current.find('a').html();
                    current.closest('.mydrop').before('<div class=\"input-mini-container clearfix\"><p class=\"name\">'+nameSkill+'</p><a href=\"#\" class=\"close\">&times;</a><input type=\"hidden\" name=\"Exercises['+idExercise+'][SkillsIds][]\" value='+dataId+' /></div>');
                    current.closest('tr').find('.save-row').show();
                }
                current.parents('.input-group-btn').removeClass('open');
                return false;
            });
            
            $('.type-exercise .new-record').live('change', function(){
                current = $(this);
                $.ajax({
                     'url': '".Yii::app()->createUrl('admin/exercises/createfromgroup', array('id_group'=>$exerciseGroup->id))."',
                     'type':'POST',
                     'data': current.serialize(),
                     'success': function(result) { 
                                     if(result==1)
                                         current.parents('.zgrid').yiiGridView('update');
                                     else if(result!='')
                                         alert(result);
                      }
                 });
            });
            

            $('.type-test tbody tr').find('textarea, input:not([name=term]), select').live('change', function(){
                $(this).closest('tr').find('.save-row').show();
            });
            
            $('.type-test tbody tr .save-row').live('click', function(){
               current = $(this);

               $.ajax({
                    'url': current.attr('href'),
                    'type':'POST',
                    'data': current.closest('tr').find('textarea, input, select').serialize(),
                    'success': function(result) { 
                        if(result==1) {
                             current.parents('.zgrid').yiiGridView('update');
                        }
                        else if(result!='')
                            alert(result);
                    }
                });
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
                    editing.val(data).siblings('.for-editor-field').html(data).closest('tr').find('.save-row').show();
                $('#htmlEditor').modal('hide');
            });
            
        });"
        
    );
?>
<div class="page-header clearfix">
    <?php echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>К тесту', array('/admin/groupofexercises/update', 'id'=>$part->Group->id), array('class'=>'btn btn-success btn-icon', 'style'=>'float:left; margin-right: 2%')); ?>
    <h2>Задания <?php echo "теста \"{$part->Group->name}\""; ?></h2>
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
$file = CHtml::fileField('ImportFile', '', array('onchange' => '$(this).hide();', 'style' => 'width:100%;'));
$this->widget('ZGridView', array(
    'id'=>"part-grid",
    'htmlOptions' => array('class'=>'clearfix zgrid type-exercise'),
    'selectableRows' => 2,
    'summaryText' => "Всего {count} заданий <div class='pull-right'>".CHtml::link('<i class="glyphicon glyphicon-remove"></i>Удалить выделенные', '#', array('class'=>'btn btn-danger btn-icon exercises-remove', 'style'=>'float:left;'))." <button onclick='$(this).hide();$(\"#import-input\").show();return false;' id='import-button' class='btn btn-success' style='margin-left:10px;'>Импорт</button><div id='import-input' style='display:none;float:left; line-height:35px; margin-left:10px;'>$file</div></div>",
    'dataProvider'=>new CArrayDataProvider($part->getExercises(false, false), array('pagination'=>false)),
    'rowHtmlOptionsExpression' => 'array("data-cansave"=>$data->canSaveFromGroup('.$part->Group->id.'), "data-id"=>$data->id)',
    'columns'=>array(
        array(
            'class' => 'CCheckBoxColumn',
            'id' => 'checked',
            'value' => '$data->id',
            'disabled' => '$data->isNewRecord',
        ),
        array(
            'name' => 'question',
            'header'=>'Текст задания',
            'type'=>'forEditor',
            'htmlOptions'=>array('width'=>'40%'),
        ),
        array(
            'name' => 'correct_answer',
            'header'=>'Правильный ответ',
            'type'=>'textArea',
            'htmlOptions'=>array('width'=>'14%'),
            'visibleCell'=>'!$data->isNewRecord',
        ),
        array(
            'name' => 'difficulty',
            'header'=>'Сложность',
            'type'=>'raw',
            'value'=>'CHtml::dropDownList("Exercises[$data->id][difficulty]", $data->difficulty, Exercises::getDataDifficulty(), array("class"=>"form-control update-record", "empty"=>"Выберите сложность"))',
            'htmlOptions'=>array('width'=>'9%'),
            'visibleCell'=>'!$data->isNewRecord',
        ),
        array(
            'name' => 'need_answer',
            'header'=>'Треб. ответ',
            'value'=>'CHtml::hiddenField("Exercises[$data->id][need_answer]", 0, array("id"=>false)) . CHtml::checkBox("Exercises[$data->id][need_answer]", $data->need_answer, array("class"=>"update-record"))',
            'type'=>'raw',
            'htmlOptions' => array('width' => '9%'),
            'visibleCell' => '!$data->isNewRecord',
        ),

        array(
            'name'=>'Skills',
            'header'=>'Требуемые умения',
            'type'=>'SkillsWithHidden',
            'htmlOptions'=>array('width'=>'15%'),
            'visibleCell'=>'!$data->isNewRecord',
        ),
        array(
                'class'=>'CButtonColumn',
                'template'=>'{save}{update}{delete}',
                'buttons'=>array(
                    'delete'=>array(
                        'url' => 'Yii::app()->createUrl("/admin/partsoftest/deleteexercise", array("id_part"=>'.$part->id.', "id_exercise"=>$data->id))',
                        'visible'=>'!$data->isNewRecord',
                    ),
                    'update'=>array(
                        'url'=>'Yii::app()->createUrl("/admin/exercises/index", array("id"=>$data->id))',
                        'visible'=>'!$data->isNewRecord',
                    ),
                    'save'=>array(
                        'label'=>'<i class="glyphicon glyphicon-floppy-disk"></i>',
                        'url'=>'Yii::app()->createUrl("admin/exercises/savechange", array("id_group"=>'.$part->Group->id.', "id_part"=>'.$part->id.'))',
                        'options'=>array('class'=>'save-row', 'title'=>'Сохранить изменения'),
                        'visible'=>'!$data->isNewRecord',
                    ),
                ),
                'htmlOptions'=>array('style'=>'width: 7%'),
        ),
    ),
)); ?>

<div class="clearfix">
<?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить глобальные задания', array('/admin/exercises/index', 'id_part'=>$part->id), array('class'=>'btn btn-sm btn-success btn-icon', 'style'=>'float: left;')) ?>
<?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить локальные задания', array('/admin/exercises/index', 'id_part'=>$part->id, 'local'=>1), array('class'=>'btn btn-sm btn-success btn-icon','style'=>'float: left; margin: 0 5px')) ?>

<div id="dropdown-generators">
    <div class="input-group-btn">
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить с помощью генератора <b class="caret"></b>', "#", array('class'=>'btn btn-sm btn-success btn-icon dropdown-toggle', 'id'=>'generators-link', 'data-toggle'=>"dropdown", "tabindex"=>"-1")) ?>
        <ul class="dropdown-menu" role="menu">
            <?php echo Generators::ListGenerators($part->id, 'part'); ?>
        </ul>
    </div>
</div>
</div>

<?php
Yii::app()->clientScript->registerScript('search' . $x . $this->id, '
                        function uploadHandle()
                        {
                            var f = $("#ImportFile"),
			    up = new uploader(f.get(0), {
				prefix:"ImportFile",
				url:"' . Yii::app()->createUrl("/admin/exercises/SWFUpload", array('id_course'=>$part->Group->id_course, 'id_part'=>$part->id)) . '",
				autoUpload:true,
				error:function(ev){ console.log("error"); remove_veil(); $("#import-button").show();$("#import-input").hide();},
				success:function(data){$(document.body).after(data);remove_veil();$("#import-button").show();$("#import-input").hide();}
			    });
                             $(".exercises-remove").click(function(){
                                if(confirm("Вы действительно хотите удалить отмеченные задания ?")) {
                                     $.ajax({
                                         url: "'.Yii::app()->createUrl('admin/partsoftest/massdeleteexercises', array('id_part'=>$part->id)).'",
                                         type:"POST",
                                         data: $("input[name*=checked]:checked").serialize(),
                                         success: function(result) { 
                                                         $("#part-grid").yiiGridView("update");
                                                         if(result!=1)
                                                             alert(result);
                                          }
                                     });
                                }
                                return false;
                            });
                        }
			$(function(){
                            	uploadHandle();
			});
		    ');
$this->endWidget();
?>
