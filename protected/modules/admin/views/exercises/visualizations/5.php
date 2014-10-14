<?php

if(!($model->isNewRecord && empty($model->Answers)))
{
    $n = 0;
    foreach($model->Answers as $answer)
    {
        $this->renderPartial('visualizations/5_variant', array('model'=>$model, 'answer'=>$answer, 'index'=>++$n));
        if($answer->is_right)
            $id_right = $n;
    }
}
?>

<script>
    $(function(){
        $('input[type=radio][name*=correct_answer]').live('change', function(){
            index = $(this).val();
            $('#right_answer_hidden').attr('name', 'Exercises[answers]['+index+'][is_right]');
        });
        
        $('#exercises-form').submit(function(){
            $return = true;
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
            return $return;
        });
    });
</script>
<div class="row" id="pick-blocks">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-2 col-md-2">
        <div class="errorMessage" id="errorCorrectAnswer"></div>
        <?php echo CHtml::link('Добавить вариант', '#', array('class'=>'btn btn-success', 'id'=>'add-variant')); ?>
    </div>
    <?php echo CHtml::hiddenField($id_right ? "Exercises[answers][$id_right][is_right]" : "", 1, array('id'=>'right_answer_hidden')); ?>
</div>
