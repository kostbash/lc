<script>
    $(function(){
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
        
        $('.spaces').live('change', function(){
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
        });
        
        $('.answers').live('change', function(){
            current = $(this);
            selected = current.find(':selected');
            space = selected.data('space');
            hiddens = $('#hidden-options input[name*=is_right][data-space='+space+']');
            hiddens.remove();
            $('#hidden-options').append('<input data-space='+space+' data-index="'+current.val()+'" type="hidden" name="Exercises[answers]['+current.val()+'][is_right]" value="1" />');
        });
        
        
        $('.add-answer').live('click', function(){
            current = $(this);
            space = $('.spaces');
            answer = $('.new-answer');
            lastHidden = $('#hidden-options input:last');
            index = lastHidden.length ? lastHidden.data('index')+1 : 0;
            hiddens = '';
            option = '';
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
        
        $('#exercises-form').submit(function(){
            $return = true;
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
                            errors += 'Для пробелa '+space.val()+' не выбран правильный ответ<br/>';
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
            
            error = $('.question-text').siblings('.errorMessage');
            if(!$('.question-text').val())
            {
                error.html('Введите текст');
                $return = false;
            } else {
                error.html('');
            }
            return $return;
        });
    });
</script>


<div id="space-text-limits">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <?php echo CHtml::textArea("Exercises[questions][0][text]", $model->Questions[0]->text, array('class'=>'form-control question-text', 'rows'=>'3', 'placeholder'=>'Введите текст')); ?>
            <div class="errorMessage"></div>
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::dropDownList('', '', $model->dataSpaces, array('class'=>'spaces form-control', 'size'=>2)); ?>
            <div class="errorMessage"></div>
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::dropDownList('', '', array(), array('id'=>false, 'class'=>'form-control answers', 'size'=>2)); ?>
            <div class="errorMessage"></div>
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::link('Удалить выделенное', '#', array('class'=>'btn btn-sm btn-danger delete-answer')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div style="font-style: italic; font-size: 13px">Примечание: чтобы создать пробел надо написать в тексте буквы sp и любое число. Например: sp1</div>
        </div>
        <div class="col-lg-offset-2 col-md-offset-2 col-lg-2 col-md-2">
                <?php echo CHtml::textField('', '', array('class'=>'form-control new-answer input-sm', 'id'=>false, 'placeholder'=>'Введите ответ')); ?>
        </div>
        <div class="col-lg-2 col-md-2">
                <?php echo CHtml::link('Добавить', '#', array('class'=>'btn btn-success btn-sm add-answer')); ?>
        </div>
    </div>
    <div id="hidden-options">
        <?php foreach($model->Answers as $answer) : ?>
            <input data-space="<?php echo $answer->number_space; ?>" data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][answer]" value="<?php echo $answer->answer; ?>">
            <?php if($answer->is_right) : ?>
                <input data-space="<?php echo $answer->number_space; ?>" data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][is_right]" value="1">
            <?php endif; ?>
            <?php if($answer->number_space) : ?>
                <input data-space="<?php echo $answer->number_space; ?>" data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][number_space]" value="<?php echo $answer->number_space; ?>">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
