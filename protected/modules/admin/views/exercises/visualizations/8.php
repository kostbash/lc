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
        
        
        $('.add-answer').live('click', function(){
            current = $(this);
            space = $('.spaces');
            answer = $('.new-answer');
            lastHidden = $('#hidden-options input:last');
            index = lastHidden.length ? lastHidden.data('index')+1 : 0;
            hiddens = '';
            option = '';
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

<div id="space-text">
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
            <?php echo CHtml::dropDownList('', '', CHtml::listData($model->rightAnswersOrderSpace, 'id', 'answer'), array('id'=>false, 'class'=>'form-control answers', 'size'=>2)); ?>
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
        <?php foreach($model->rightAnswersOrderSpace as $answer) : ?>
            <input data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][answer]" value="<?php echo $answer->answer; ?>">
        <?php endforeach; ?>
    </div>
</div>
