<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/ckeditor/ckeditor.js"); ?>
<script type="text/javascript">
    $(function(){
        $('#searchSkill').keyup(function(e)
        {
            current = $(this);
            skills = current.closest('#add-skills').find('.skills .skill');
            skillsIds = new Array();
            firstIds = [<?php echo implode(',', $groupExercise->IdsUsedSkills); ?>];
            skills.each(function(i, skill){
                skillsIds[i] = $(skill).data('id');
            });
            
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/exercises/skillsnotidsajax', array('id_course'=>$groupExercise->id_course)); ?>',
                type:'POST',
                data: { term: current.val(), Exercises:{0:{SkillsIds: skillsIds, firstIds: firstIds}} },
                dataType: 'json',
                success: function(result) {
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result.html);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
            return false;
        });
        
        $('#add-skills .dropdown-toggle').click(function(e) {
            current = $(this);
            skills = current.closest('#add-skills').find('.skills .skill');
            skillsIds = new Array();
            firstIds = [<?php echo implode(',', $groupExercise->IdsUsedSkills); ?>];
            skills.each(function(i, skill){
                skillsIds[i] = $(skill).data('id');
            });
            
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/exercises/skillsnotidsajax', array('id_course'=>$groupExercise->id_course)); ?>',
                type:'POST',
                data: { term: current.val(), Exercises:{0:{SkillsIds: skillsIds, firstIds: firstIds}} },
                dataType: 'json',
                success: function(result) { 
                    if(result) {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result.html);
                    }
                }
            });
        
        });

        $('#add-skills .dropdown-menu li').live('click', function(){
            current = $(this);
            id = current.data('id');
            if(id)
            {
                skillsContainer = current.closest('#add-skills').find('.skills');
                $.ajax({
                    url:'<?php echo Yii::app()->createUrl('admin/skills/gethtmlmini'); ?>',
                    type:'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success) {
                            skillsContainer.append(result.html);
                        }
                    }
                });
            }
            current.closest('.input-group-btn').removeClass('open');
            return false;
        });
        
        $('.skill .remove').live('click', function(){
            $(this).closest('.skill').remove();
        });
        
        $('.for-editor-field').live('click', function() {
            current = $(this);
            hidden = current.siblings('input[type=hidden]');
            if(hidden.attr('id')!='editing')
            {
                $('#editing').removeAttr('id');
                hidden.attr('id', 'editing');
                editor = CKEDITOR.instances['editor-text'];
                editor.setData(hidden.val());
            }
            $('#htmlEditor').modal('show');
        });

        $('#amend').click(function() {
            data = CKEDITOR.instances['editor-text'].getData();
            editing = $('#editing');
            if(!$.trim(data))
            {
                alert('Задание не может быть пустым !');
                return false;
            }
            if(editing.val()!=data)
            {
                editing.val(data);
                editing.siblings('.for-editor-field').html(data);
            }
            $('#htmlEditor').modal('hide');
        });
        
        $('#Exercises_id_visual').change(function(){
            vis = $('#visualization');
            vis.find('.row').remove();
            idVisual = $(this).val();
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/exercises/gethtmlvisual'); ?>',
                data: { id_visual: idVisual, id_group: $('#idGroup').val(), id_part: $('#idPart').val() },
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    if(result.success) {
                        vis.append(result.html).removeClass('hide');
                        vis.attr('data-visual', idVisual);
                    }
                }
            });
        });
        
        $('#add-variant').live('click', function(){
            current = $(this);
            lastAnswer = $('.variant:last');
            index = lastAnswer.length ? lastAnswer.data('index')+1 : 1;
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/exercises/gethtmlvariant'); ?>',
                data: { index: index, id_visual: $('#visualization').data('visual') },
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                        current.closest('.row').before(result.html);
                }
            });
            return false;
        });
        
        $('.delete-variant').live('click', function(){
            if(confirm('Вы действительно хотите удалить вариант ответа ?'))
            {
                $(this).closest('.row').remove();
            }
            return false;
        });
        
        $('#exercises-form').submit(function(){
            $return = true;
            difficulty = $('#Exercises_difficulty');
            if(!difficulty.val())
            {
                difficulty.siblings('.errorMessage').html('Введите сложность задания');
                $return = false;
            } else {
                difficulty.siblings('.errorMessage').html('');
            }
            visual = $('#Exercises_id_visual');
            if(visual.length && !visual.val())
            {
                visual.siblings('.errorMessage').html('Выберите тип визуализации');
                $return = false;
            } else {
                visual.siblings('.errorMessage').html('');
            }
            
            condition = $('input[name*=condition]');
            if(!condition.val())
            {
                condition.siblings('.errorMessage').html('Введите условие');
                $return = false;
            } else {
                condition.siblings('.errorMessage').html('');
            }
            
            return $return;
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

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exercises-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
    
<?php if(!$model->isNewRecord) : ?>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <?php echo CHtml::label('Тип', ''); ?>
        </div>
        <div class="col-lg-5 col-md-5">
            <?php echo $model->Type->name.', '.$model->Visual->name;  ?>
        </div>
    </div>
<?php endif; ?>
<?php
    if($id_group)
    {
        echo CHtml::hiddenField('id_group', $id_group, array('id'=>'idGroup'));
    }
    elseif($id_part)
    {
        echo CHtml::hiddenField('id_part', $id_part, array('id'=>'idPart'));
    }
?>
<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo CHtml::label('Умения', ''); ?>
    </div>
    <div class="col-lg-5 col-md-5">
        <div id="add-skills">
            <div class="skills-mini">
                <div class="skills">
                    <?php 
                        if($model->Skills)
                            foreach($model->Skills as $skill)
                                $this->renderPartial("../skills/_skill_mini", array('model'=>$skill));
                    ?>
                </div>
            </div>
            <div class="input-group mydrop ">
                <?php echo CHtml::textField("Skills[name]", '', array('placeholder'=>'Введите название умения', 'class'=>'form-control input-sm', 'id'=>'searchSkill', 'autocomplete'=>'off')); ?>
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
    
<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo $form->label($model, 'difficulty'); ?>
    </div>
    
    <div class="col-lg-5 col-md-5">
        <?php echo $form->dropDownList($model, 'difficulty', Exercises::getDataDifficulty(), array('class'=>'form-control', 'empty'=>'Введите сложность')); ?>
        <div class="errorMessage"></div>
    </div>
</div>

<?php if($model->isNewRecord && $model->id_type!=4) : ?>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <?php echo $form->label($model, 'id_visual'); ?>
        </div>

        <div class="col-lg-5 col-md-5">
            <?php echo $form->dropDownList($model, 'id_visual', ExercisesVisuals::getDataVisuals($model->id_type), array('class'=>'form-control', 'empty'=>'Выберите тип визуализации')); ?>
            <div class="errorMessage"></div>
        </div>
    </div>
<?php endif; ?>  
    
<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo $form->label($model, 'condition'); ?>

    </div>
    
    <div class="col-lg-8 col-md-8">
        <?php echo CHtml::hiddenField(get_class($model)."[condition]", $model->condition); ?>
        <div class='for-editor-field' title='Нажмите, чтобы открыть редактор'>
            <?php echo $model->condition ? $model->condition : 'Введите текст'; ?>
        </div>
        <div class="errorMessage"></div>
    </div>
</div>

<div class="section<?php if(!$model->id_visual) echo ' hide'; ?>" id='visualization' data-visual="<?php echo $model->id_visual; ?>">
    <h2>Визуализация</h2>
    <?php if($model->id_type=='8') $this->widget("application.modules.admin.widgets.ButtonsWidget"); ?>
    <?php if($model->id_visual) $this->renderPartial("visualizations/{$model->id_visual}", array('model'=>$model, 'id_group'=>$id_group, 'id_part'=>$id_part, 'id_map'=>$id_map)); ?>
</div>
    
<?php $this->endWidget(); ?>
</div>