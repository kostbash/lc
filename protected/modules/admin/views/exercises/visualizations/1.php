<script>
    $(function(){
        $('#exercises-form').submit(function(){
            $return = true;    
            answers = $('input[name*=answers][type=text]');
            if(answers.length)
            {
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
                $('#errorCorrectAnswer').html('');
            } else {
                $('#errorCorrectAnswer').html('Добавьте вариант ответа');
                $return = false;
            }
            return $return;
        });
    });
</script>

<?php
if(!($model->isNewRecord && empty($model->Answers)))
{
    $n = 0;
    foreach($model->Answers as $answer)
    {
        $this->renderPartial('visualizations/1_variant', array('model'=>$model, 'answer'=>$answer, 'index'=>++$n));
    }
}
?>

<div class="row" id="exact-answers">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-2 col-md-2">
        <div class="errorMessage" id="errorCorrectAnswer"></div>
        <?php echo CHtml::link('Добавить вариант', '#', array('class'=>'btn btn-success', 'id'=>'add-variant')); ?>
    </div>
</div>
