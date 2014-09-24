<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/ckeditor/ckeditor.js"); ?>
<script type="text/javascript">
    $(function(){
        $('#searchSkill').keyup(function(e)
        {
            current = $(this);
            skills = current.closest('#add-skills').find('.skills .skill');
            skillsIds = new Array();
            skills.each(function(i, skill){
                skillsIds[i] = $(skill).data('id');
            });
            
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/exercises/skillsnotidsajax'); ?>',
                type:'POST',
                data: { term: current.val(), skillsIds: skillsIds },
                success: function(result) {
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
            return false;
        });
        
        $('#add-skills .dropdown-toggle').click(function(e) {
            current = $(this);
            skills = current.closest('#add-skills').find('.skills .skill');
            skillsIds = new Array();
            skills.each(function(i, skill){
                skillsIds[i] = $(skill).data('id');
            });
            
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/exercises/skillsnotidsajax'); ?>',
                type:'POST',
                data: { term: current.val(), skillsIds: skillsIds },
                success: function(result) { 
                    if(result) {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result);
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
                //$('.cke_wysiwyg_frame body').focus();
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
                data: { id_visual: idVisual },
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
        
        $('.add-option').live('click', function(){
            option = $('#option-name');
            if(option.val())
            {
                lastOption = $('#Exercises_correct_answers option:last-child');
                maxIndex = lastOption.length ? parseInt(lastOption.val())+1 : 0;
                $('#Exercises_correct_answers').append('<option value='+maxIndex+'>'+ option.val() +'</option>');
                $('#hidden-options').append('<input data-index="'+maxIndex+'" type="hidden" name="Exercises[answers]['+maxIndex+'][answer]" value="'+ option.val() +'" />');
                option.val('');
            } else {
                alert('Введите название нового варианта ответа');
            }
            return false;
        });
        
        $('.delete-option').live('click', function(){
            if(confirm('Вы действительно хотите удалить вариант ответа ?'))
            {
                selected = $('#Exercises_correct_answers option:selected');
                selected.each(function(n, answer){
                    $('#hidden-options input[data-index='+ $(answer).val() +']').remove();
                });
                selected.remove();
            }
            return false;
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
        
        $('#Exercises_correct_answers').live('change', function(){
            $('#hidden-options input[name*=is_right]').remove();
            $(this).find('option:selected').each(function(n, option){
                index = $(option).val();
                $('#hidden-options').append('<input data-index="'+index+'" type="hidden" name="Exercises[answers]['+index+'][is_right]" value="1" />');
            });
        });
        
        $('input[type=radio][name*=correct_answer]').live('change', function(){
            index = $(this).val();
            $('#right_answer_hidden').attr('name', 'Exercises[answers]['+index+'][is_right]');
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
            correctAnswers = $('#Exercises_correct_answers');
            if(correctAnswers.length && !correctAnswers.val())
            {
                correctAnswers.siblings('.errorMessage').html('Введите/выберите правильный ответ');
                $return = false;
            } else {
                correctAnswers.siblings('.errorMessage').html('');
            }
            
            // если выбор блока
            if($('#pick-blocks').length)
            {
                // для радио кнопок
                correctAnswers = $('input[name*=correct_answers]:checked');
                if(!(correctAnswers.length && correctAnswers.val()))
                {
                    $('#errorCorrectAnswer').html('Выберите правильный ответ');
                    $return = false;
                } else {
                    $('#errorCorrectAnswer').html('');
                }

                answers = $('.hidden-answer');
                answers.each(function(n, answer){
                    answer = $(answer);
                    if(!answer.val())
                    {
                        answer.siblings('.errorMessage').html('Введите текст ответа');
                        $return = false;
                    } else {
                        answer.siblings('.errorMessage').html('');
                    }
                });
            }
            
            // если сопоставление
            if($('#comparisons').length)
            {
                answers = $('.hidden-answer');
                answers.each(function(n, answer){
                    answer = $(answer);
                    if(!answer.val())
                    {
                        answer.siblings('.errorMessage').html('Введите текст варианта сопоставления');
                        $return = false;
                    } else {
                        answer.siblings('.errorMessage').html('');
                    }
                });
            }
            
            if($('#exact-answers').length)
            {
                // для радио кнопок
                correctAnswers = $('input[name*=answers][type=text]');
                if(correctAnswers.length)
                {
                    correctAnswers.each(function(n, answer){
                        answer = $(answer);
                        if(!answer.val())
                        {
                            answer.siblings('.errorMessage').html('Введите текст ответа');
                            $return = false;
                        } else {
                            answer.siblings('.errorMessage').html('');
                        }
                    });
                    $('#errorCorrectAnswer').html('');
                } else {
                    $('#errorCorrectAnswer').html('Добавьте вариант ответа');
                    $return = false;
                }
            }
            
            if($('#sentences').length)
            {
                sentences = $('textarea[name*=answers]');
                if(sentences.length)
                {
                    sentences.each(function(n, answer){
                        answer = $(answer);
                        if(!answer.val())
                        {
                            answer.siblings('.errorMessage').html('Введите предложение');
                            $return = false;
                        } else {
                            answer.siblings('.errorMessage').html('');
                        }
                    });
                } else {
                    $return = false;
                }
            }
            
            if($('#sentences').length)
            {
                sentences = $('textarea[name*=answers]');
                if(sentences.length)
                {
                    sentences.each(function(n, answer){
                        answer = $(answer);
                        if(!answer.val())
                        {
                            answer.siblings('.errorMessage').html('Введите предложение');
                            $return = false;
                        } else {
                            answer.siblings('.errorMessage').html('');
                        }
                    });
                } else {
                    $return = false;
                }
            }
            
            if($('.question-text').length)
            {
                error = $('.question-text').siblings('.errorMessage');
                if(!$('.question-text').val())
                {
                    error.html('Введите текст');
                    $return = false;
                } else {
                    error.html('');
                }
            }
            
            if($('#space-text-limits').length)
            {
                spacesCont = $('.spaces');
                spaces = spacesCont.find('option');
                errorsDiv = spacesCont.siblings('.errorMessage'); 
                if(spaces.length)
                {
                    errors = '';
                    spaces.each(function(n, space){
                        space = $(space);
                        if(space.val())
                        {
                            right = $('#hidden-options input[data-space='+space.val()+'][name*=is_right]');
                            if(!right.length)
                            {
                                errors += 'Для пробела'+space.val()+' не выбран правильный ответ<br/>';
                                $return = false;
                            }
                        }
                    });
                    if(errors)
                    {
                        errorsDiv.html(errors);
                        $return = false;
                    } else {
                        errorsDiv.html('');
                    }
                } else {
                    errorsDiv.html('Не создано ни одного пробела');
                    $return = false;
                }
            }
            
            if($('#space-text').length)
            {
                spacesCont = $('.spaces');
                spaces = spacesCont.find('option');
                errorsDiv = spacesCont.siblings('.errorMessage'); 
                if(spaces.length)
                {
                    if(spaces.length != $('.answers').find('option').length)
                    {
                        errorsDiv.html('Кол-во пробелов должно быть равно кол-ву ответов');
                        $return = false;
                    } else {
                        errorsDiv.html('');
                    }
                } else {
                    errorsDiv.html('Не создано ни одного пробела');
                    $return = false;
                }
            }
            
            return $return;
        });
        
        $('.question-text').live('change', function() {
            question = $(this);
            val = question.val();
            template = /sp(\d+)/ig;
            data = new Array();
            i = 0;
            
            while ( (res = template.exec(val)) != null )
            {
              variable = $('.spaces option[value='+res[0]+']');
              if(variable.length)
                  variable.addClass('dont-remove');
              else {
                data[res[1]] = res[0];
              }
            }
            
            $('.spaces option:not(.dont-remove)').remove();
            
            if(data.length)
            {
                for(var space in data)
                {
                    $('.spaces').append('<option value='+space+'>Пробел '+space+'</option>');
                }
            }
            return false;
        });
        
        $('.question-text-field').live('change', function() {
            question = $(this);
            val = question.val();
            template = /sp(\d+)/ig;
            data = new Array();
            i = 0;
            $('#spaces .variant').removeClass('dont-remove');
            while ( (res = template.exec(val)) != null )
            {
              variable = $('#spaces .variant[data-number='+res[1]+']');
              if(variable.length)
                  variable.addClass('dont-remove');
              else {
                data[res[1]] = res[0];
              }
            }
            
            $('#spaces .variant:not(.dont-remove)').remove();
            
            if(data.length)
            {
                for(var space in data)
                {
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl('admin/exercises/gethtmlvariant'); ?>',
                        data: { index: space, id_visual: $('#visualization').data('visual') },
                        type: 'POST',
                        dataType: 'json',
                        success: function(result) {
                            if(result.success)
                                $('#spaces').append(result.html);
                        }
                    });
                }
            }
            return false;
        });
        
        $('.spaces').live('change', function(){
            if($('#space-text-limits').length)
            {
                current = $(this);
                number = current.val();
                options = '';
                hiddens = $('#hidden-options input[name*=number_space][value='+number+']');
                hiddens.each(function(n, field)
                {
                    field = $(field);
                    answer = $('#hidden-options input[name$="[answer]"][data-index='+field.data('index')+']');
                    right = $('#hidden-options input[name$="[is_right]"][data-index='+field.data('index')+']');
                    if(answer.length)
                    {
                        if(right.length)
                        {
                            options += '<option data-space='+number+' selected=selected value='+answer.data('index')+'>'+answer.val()+'</option>';
                        } else {
                            options += '<option data-space='+number+' value='+answer.data('index')+'>'+answer.val()+'</option>';
                        }
                    }
                });
                $('.answers').html(options);
            }
        });
        
        $('.answers').live('change', function(){
            if($('#space-text-limits').length)
            {
                current = $(this);
                selected = current.find(':selected');
                space = selected.data('space');
                hiddens = $('#hidden-options input[name*=is_right][data-space='+space+']');
                hiddens.remove();
                $('#hidden-options').append('<input data-space='+space+' data-index="'+current.val()+'" type="hidden" name="Exercises[answers]['+current.val()+'][is_right]" value="1" />');
            }
        });
        
        $('.add-answer').live('click', function(){
            current = $(this);
            space = $('.spaces');
            answer = $('.new-answer');
            lastHidden = $('#hidden-options input:last');
            index = lastHidden.length ? lastHidden.data('index')+1 : 0;
            hiddens = '';
            option = '';
            if($('#space-text-limits').length)
            {
                if(space.val())
                {
                    if(answer.val())
                    {
                        hiddens += '<input data-space='+space.val()+' data-index="'+index+'" type="hidden" name="Exercises[answers]['+index+'][answer]" value="'+answer.val()+'" />';
                        hiddens += '<input data-space='+space.val()+' data-index="'+index+'" type="hidden" name="Exercises[answers]['+index+'][number_space]" value="'+space.val()+'" />';
                        option += '<option data-space='+space.val()+' value='+index+'>'+answer.val()+'</option>';
                        $('.answers').append(option);
                        $('#hidden-options').append(hiddens);
                        answer.val('');
                    } else {
                        alert('Введите вариант ответа');
                        answer.focus();
                    }  
                } else {
                    alert('Выберите пробел');
                }
            } else {
                if(space.find('option').length > $('.answers').find('option').length)
                {
                    if(answer.val())
                    {
                        hiddens += '<input data-index="'+index+'" type="hidden" name="Exercises[answers]['+index+'][answer]" value="'+answer.val()+'" />';
                        option += '<option value='+index+'>'+answer.val()+'</option>';
                        $('.answers').append(option);
                        $('#hidden-options').append(hiddens);
                        answer.val('');
                    } else {
                        alert('Введите вариант ответа');
                        answer.focus();
                    }
                } else {
                    alert('Количество ответов не может превышать кол-во пробелов');
                }
            }
            return false;
        });
        
        $('.delete-answer').live('click', function(){
            current = $(this);
            selected = $('.answers :selected');
            if(selected.length)
            {
                hiddens = $('#hidden-options input[data-index='+selected.val()+']');
                hiddens.remove();
                selected.remove();
            }
            return false;
        });
    });

</script>

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

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exercises-form',
	'enableAjaxValidation'=>false,
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
    <?php if($model->id_visual) $this->renderPartial("visualizations/{$model->id_visual}", array('model'=>$model)); ?>
</div>
    
<?php $this->endWidget(); ?>
</div>