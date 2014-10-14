<script>
    $(function(){
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
            selected = $('#Exercises_correct_answers option:selected');
            if(selected.length)
            {
                if(confirm('Вы действительно хотите удалить вариант ответа ?'))
                {
                    selected.each(function(n, answer){
                        $('#hidden-options input[data-index='+ $(answer).val() +']').remove();
                    });
                    selected.remove();
                }
            }
            else
            {
                alert('Не выбран вариант для удаления');
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
        
        $('#exercises-form').submit(function(){
            $return = true;
            correctAnswers = $('#Exercises_correct_answers');
            if(correctAnswers.length && !correctAnswers.val())
            {
                correctAnswers.siblings('.errorMessage').html('Выберите правильный ответ');
                $return = false;
            } else {
                correctAnswers.siblings('.errorMessage').html('');
            }
            return $return;
        });
    });
</script>

<div id="radio-buttons">
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <?php echo CHtml::label('Варианты ответов', ''); ?>
        </div>
        <div class="col-lg-5 col-md-5">
            <?php echo CHtml::dropDownList('Exercises[correct_answers]', $model->idsRightAnswers, CHtml::listData($model->Answers, 'id', 'answer'), array('class'=>'form-control', 'placeholder'=>'Введите правильный ответ', 'size'=>2)); ?>
            <div class="errorMessage"></div>
            <div id="hidden-options">
                <?php foreach($model->Answers as $answer) : ?>
                    <input data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][answer]" value="<?php echo $answer->answer; ?>">
                    <?php if($answer->is_right) : ?>
                        <input data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][is_right]" value="1">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::link('Удалить выделенный', '#', array('class'=>'btn btn-danger delete-option')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-offset-3 col-md-offset-3 col-lg-3 col-md-3">
            <?php echo CHtml::textField('option-name', '', array('class'=>'form-control', 'id'=>'option-name', 'placeholder'=>'Введите название нового варианта')); ?>
        </div>
        <div class="col-lg-2 col-md-2" style='text-align: right;'>
            <?php echo CHtml::link('Добавить', '#', array('class'=>'btn btn-success add-option')); ?>
        </div>
    </div>
</div>
