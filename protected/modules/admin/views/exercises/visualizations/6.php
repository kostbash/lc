<?php
if(!($model->isNewRecord && empty($model->Comparisons)))
{
    $n = 0;
    foreach($model->Comparisons as $comparison)
    {
        $this->renderPartial('visualizations/6_variant', array('model'=>$model, 'comparison'=>$comparison, 'index'=>++$n));
    }
}
else
{
    $this->renderPartial('visualizations/6_variant', array('model'=>$model, 'comparison'=>new Exercisescomparisons, 'index'=>1));
}
?>

<script>
    $(function(){
        $('#exercises-form').submit(function(){
            $return = true;
            answers = $('.hidden-answer');
            if(answers.length)
            {
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
            else
            {
                alert('Добавьте сравнение');
                $return = false;
            }
            return $return;
        });
        
        $('.answer-one').live('keydown', function(e) {
            toNext = e.ctrlKey && e.keyCode == 13 ? true : false;
            if(toNext)
            {
                answerOne = $(this);
                answerTwo = answerOne.closest('.variant').find('.answer-two');
                answerTwo.focus();
            }
        });
        
        $('.answer-two').live('keydown', function(e) {
            toNext = e.ctrlKey && e.keyCode == 13 ? true : false;
            if(toNext)
            {
                answerTwo = $(this);
                thisComparisons = answerTwo.closest('.variant');
                nextComparisons = thisComparisons.next('.variant');
                if(nextComparisons.length)
                {
                    nextAnswerOne = nextComparisons.find('.answer-one');
                    nextAnswerOne.focus();
                }
                else
                {
                    index = thisComparisons.data('index')+1;
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl('admin/exercises/gethtmlvariant'); ?>',
                        data: { index: index, id_visual: $('#visualization').data('visual') },
                        type: 'POST',
                        dataType: 'json',
                        success: function(result) {
                            if(result.success)
                                thisComparisons.after(result.html);
                                nextComparisons = thisComparisons.next('.variant');
                                nextAnswerOne = nextComparisons.find('.answer-one');
                                nextAnswerOne.focus();
                        }
                    });
                }
            }
        });
    });
</script>

<div class="row" id="comparisons">
    <div class="col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3">
        <div class="errorMessage"></div>
        <?php echo CHtml::link('Добавить сравнение', '#', array('class'=>'btn btn-success', 'id'=>'add-variant', 'style'=>'float: right')); ?>
    </div>
</div>
