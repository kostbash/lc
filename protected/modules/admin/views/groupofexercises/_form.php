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
            
            $('#addSkill #searchSkill').live('keyup', function(){
                current = $(this);
                $.ajax({
                    'url':'".Yii::app()->createUrl('admin/groupofexercises/skillsbyajax', array('id'=>$exerciseGroup->id))."',
                    'type':'POST',
                    'data': { term: current.val() },
                    'success': function(result) { 
                            current.siblings('.input-group-btn').children('.dropdown-menu').children('li').remove();
                            current.siblings('.input-group-btn').children('.dropdown-menu').append(result);
                            current.siblings('.input-group-btn').addClass('open');
                    }
                });
            });
            
            $('#addSkill .dropdown-toggle').live('click', function(){
                current = $(this);
                    $.ajax({
                        'url':'".Yii::app()->createUrl('admin/groupofexercises/skillsbyajax', array('id'=>$exerciseGroup->id))."',
                        'type':'POST',
                        'success': function(result) { 
                            if(result!='')
                                current.siblings('.dropdown-menu').children('li').remove();
                                current.siblings('.dropdown-menu').append(result);
                        }
                    });
            });
            
            $('#addSkill .dropdown-menu li').live('click', function(){
                current = $(this);
                input = current.parents('.input-group-btn').siblings('input');
                $.ajax({
                    url: '".Yii::app()->createUrl('admin/groupofexercises/addskill')."',
                    type:'POST',
                    data:{ 'id_skill': current.data('id'), id_group: ".$exerciseGroup->id." },
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success) {
                            $('#skills-grid').yiiGridView('update');
                        }
                    }
                });
                current.parents('.input-group-btn').removeClass('open');
                return false;
            });
            
            $('.type-exercise tbody tr').find('textarea, input:not([name=term]), select').live('change', function(){
                $(this).closest('tr').find('.save-row').show();
            });
            
            $('.type-exercise tbody tr .save-row').live('click', function(){
               current = $(this);
               if(current.closest('tr').data('cansave') != 1)
                    if(!confirm('Создать задание на основании измененных данных ?'))
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
            
            $('.only-number').live('change keyup input click', function() {
                if (this.value.match(/[^0-9]/g))
                    this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('.exercise-order .glyphicon-arrow-up').live('click', function() {
                currentExercise = $(this).closest('tr');
                upExercise = currentExercise.prev('tr');
                $.ajax({
                    'url': '".Yii::app()->createUrl('admin/groupandexercises/changeorderexercise')."?id_group='+currentExercise.closest('.exercisegroup').data('id'),
                    'type':'POST',
                    'data': {id_exercise: currentExercise.data('id'), id_sibling_exercise: upExercise.data('id')},
                    'success': function(result) { 
                                    if(result==1)
                                        currentExercise.after(upExercise);
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });
            
            $('.exercise-order .glyphicon-arrow-down').live('click', function() {
                currentExercise = $(this).closest('tr');
                downExercise = currentExercise.next('tr');
                $.ajax({
                    'url': '".Yii::app()->createUrl('admin/groupandexercises/changeorderexercise')."?id_group='+currentExercise.closest('.exercisegroup').data('id'),
                    'type':'POST',
                    'data': {id_exercise: currentExercise.data('id'), id_sibling_exercise: downExercise.data('id')},
                    'success': function(result) { 
                                    if(result==1)
                                        currentExercise.before(downExercise);
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });
            
            $('.part-order .glyphicon-arrow-up').live('click', function() {
                currentCriteria = $(this).closest('tr');
                upCriteria = currentCriteria.prev('tr');
                $.ajax({
                    'url': '".Yii::app()->createUrl('admin/partsoftest/changeorder', array('id_group'=>$exerciseGroup->id))."',
                    'type':'POST',
                    'data': {id_criteria: currentCriteria.data('id'), id_sibling_criteria: upCriteria.data('id')},
                    'success': function(result) { 
                                    if(result==1)
                                        currentCriteria.after(upCriteria);
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });
            
            $('.part-order .glyphicon-arrow-down').live('click', function() {
                currentCriteria = $(this).closest('tr');
                downCriteria = currentCriteria.next('tr');
                $.ajax({
                    'url': '".Yii::app()->createUrl('admin/partsoftest/changeorder', array('id_group'=>$exerciseGroup->id))."',
                    'type':'POST',
                    'data': {id_criteria: currentCriteria.data('id'), id_sibling_criteria: downCriteria.data('id')},
                    'success': function(result) { 
                                    if(result==1)
                                        currentCriteria.before(downCriteria);
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });
            
            $('#main input, #main select').live('change', function() {
                current = $(this);
                if(current.is('[name*=type]')) {
                    if(!confirm('Все задания группы удаляться, продолжить ?'))
                        return false;
                }
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/groupofexercises/updatebyajax')."',
                    type:'POST',
                    data: current.serialize(),
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success) {
                            if(result.needUpdate) {
                                location.reload();
                            }
                        }
                    }
                });
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

<div class="form">
    
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

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lessons-form',
	'enableAjaxValidation'=>false,
)); ?>
  <div class="section" id="main">
        <h3 class="head">Основное</h3>
        <div class="row">
            <div class="col-lg-5 col-md-5">
                <?php echo CHtml::textField("GroupOfExercises[$exerciseGroup->id][name]", $exerciseGroup->name, array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder' => 'Введите название урока')); ?>
            </div>
            <div class="col-lg-3 col-md-3">
                <?php echo CHtml::dropDownList("GroupOfExercises[$exerciseGroup->id][type]", $exerciseGroup->type, GroupOfExercises::$typeGroup, array('class'=>'form-control')); ?>
            </div>
        </div>
   </div>
   <div class="section skills">
        <h3 class="head">Умения</h3>
        <div class="row">
            <div class="col-lg-5 col-md-5">
                <?php $this->widget('ZGridView', array(
                        'id'=>'skills-grid',
                        'dataProvider'=> $skills->search(),
                        'columns'=>array(
                            array(
                                'name' => 'Skill.name',
                                'header'=>'Умение',
                                'htmlOptions'=>array('width'=>'50%'),
                            ),
                            array(
                                'name'=>'pass_percent',
                                'type'=>'raw',
                                'value'=>'CHtml::dropDownList("GroupOfExercises[$data->id_group][Skills][$data->id_skill][pass_percent]", $data->pass_percent, LessonAndSkills::getListDataPercents(), array("class"=>"form-control update-record", "empty"=>"Выберите процент"))',
                                'visibleCell'=>'!$data->isNewRecord',
                                'htmlOptions'=>array('width'=>'40%'),
                            ),
                            array(
                                    'class'=>'CButtonColumn',
                                    'template'=>'{delete}',
                                    'buttons'=>array(
                                        'delete'=>array(
                                            'url' => 'Yii::app()->createUrl("admin/groupofexercises/removeskill", array("id"=>$data->id))',
                                            'visible'=>'!$data->isNewRecord',
                                        ),
                                    ),
                                    'htmlOptions'=>array('style'=>'width: 10%'),
                            ),
                        ),
                )); ?>
            </div>
	</div>
        <div class="row">
            <div id="addSkill" class="col-lg-5 col-md-5">
                <div class="input-group mydrop" id="courseSkill">
                    <?php echo CHtml::textField("searchSkill", '', array('placeholder'=>'Введите название умения', 'class'=>'form-control input-sm')); ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section exercisegroup-container">
        <h3 class="head">Задания</h3>
        <div class="exercisegroup" data-id=<?php echo $exerciseGroup->id; ?> >
        <?php if($exerciseGroup->type == 1) : ?>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                    <?php $this->widget('ZGridView', array(
                            'id'=>"exerciseGroup-$exerciseGroup->id-grid",
                            'htmlOptions' => array('class'=>'clearfix zgrid type-exercise'),
                            'dataProvider'=>new CArrayDataProvider($exerciseGroup->Exercises, array('pagination'=>false)),
                            'rowHtmlOptionsExpression' => 'array("data-cansave"=>$data->canSaveFromGroup('.$exerciseGroup->id.'), "data-id"=>$data->id)',
                            'columns'=>array(
                                array(
                                    'header'=>'',
                                    'type'=>'raw',
                                    'value'=>'"<div class=\"exercise-order order-rows\">
                                                    <i class=\"glyphicon glyphicon-arrow-up\" title=\"переместить вверх\"></i>
                                                    <i class=\"glyphicon glyphicon-arrow-down\" title=\"переместить вниз\"></i>
                                                </div>"',
                                    'visibleCell'=>'!$data->isNewRecord',
                                    'htmlOptions'=>array('width'=>'5%'),
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
                                                'url' => 'Yii::app()->createUrl("/admin/groupandexercises/delete", array("id_group"=>'.$exerciseGroup->id.', "id_exercise"=>$data->id))',
                                                'visible'=>'!$data->isNewRecord',
                                            ),
                                            'update'=>array(
                                                'url'=>'Yii::app()->createUrl("/admin/exercises/index", array("id"=>$data->id))',
                                                'visible'=>'!$data->isNewRecord',
                                            ),
                                            'save'=>array(
                                                'label'=>'<i class="glyphicon glyphicon-floppy-disk"></i>',
                                                'url'=>'Yii::app()->createUrl("admin/exercises/savechange", array("id_group"=>'.$exerciseGroup->id.'))',
                                                'options'=>array('class'=>'save-row', 'title'=>'Сохранить изменения'),
                                                'visible'=>'!$data->isNewRecord',
                                            ),
                                        ),
                                        'htmlOptions'=>array('style'=>'width: 7%'),
                                ),
                            ),
                    )); ?>
                    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить глобальные задания', array('/admin/exercises/index', 'id_group'=>$exerciseGroup->id), array('class'=>'btn btn-sm btn-success btn-icon')) ?>
                    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить локальные задания', array('/admin/exercises/index', 'id_group'=>$exerciseGroup->id, 'local'=>1), array('class'=>'btn btn-sm btn-success btn-icon')) ?>
            </div>
        </div>
        <?php elseif($exerciseGroup->type == 2) : ?>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-lg-offset-1 col-md-offset-1">
                    <?php $this->widget('ZGridView', array(
                            'id'=>"exerciseGroup-$exerciseGroup->id-grid",
                            'htmlOptions' => array('class'=>'clearfix zgrid type-test'),
                            'dataProvider'=>new CArrayDataProvider($exerciseGroup->PartsOfTest, array('pagination'=>false)),
                            'rowHtmlOptionsExpression' => 'array("data-id"=>$data->id)',
                            'columns'=>array(
                                array(
                                    'header'=>'',
                                    'type'=>'raw',
                                    'value'=>'"<div class=\"part-order order-rows\">
                                                    <i class=\"glyphicon glyphicon-arrow-up\" title=\"переместить вверх\"></i>
                                                    <i class=\"glyphicon glyphicon-arrow-down\" title=\"переместить вниз\"></i>
                                                </div>"',
                                    'htmlOptions'=>array('width'=>'10%'),
                                ),
                                array(
                                    'header'=>'Число заданий',
                                    'value'=>'$data->CountExercises',
                                ),
                                array(
                                    'name'=>'limit',
                                    'header'=>'Число заданий теста',
                                    'type' => 'raw',
                                    'value'=>'CHtml::textField("PartsOfTest[$data->id][limit]", $data->limit, array("class"=>"form-control update-record only-number", "placeholder"=>"число заданий"))',
                                    'htmlOptions'=>array('width'=>'30%'),
                                ),
                                
                                array(
                                        'class'=>'CButtonColumn',
                                        'template'=>'{save}{update}{delete}',
                                        'buttons'=>array(
                                            'delete'=>array(
                                                'url' => 'Yii::app()->createUrl("/admin/partsoftest/delete", array("id"=>$data->id))',
                                            ),
                                            'update'=>array(
                                                'url'=>'Yii::app()->createUrl("/admin/partsoftest/update", array("id"=>$data->id))',
                                            ),
                                            'save'=>array(
                                                'label'=>'<i class="glyphicon glyphicon-floppy-disk"></i>',
                                                'url'=>'Yii::app()->createUrl("/admin/partsoftest/savechange")',
                                                'options'=>array('class'=>'save-row', 'title'=>'Сохранить изменения'),
                                            ),
                                        ),
                                        'htmlOptions'=>array('style'=>'width: 15%'),
                                ),
                            ),
                    )); ?>
                    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить часть из глобальных заданий', array('/admin/exercises/index', 'id_group'=>$exerciseGroup->id), array('class'=>'btn btn-sm btn-success btn-icon')) ?>
                    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить часть из локальных заданий', array('/admin/exercises/index', 'id_group'=>$exerciseGroup->id, 'local'=>1), array('class'=>'btn btn-sm btn-success btn-icon')) ?>
            </div>
        </div>
        <?php endif; ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->