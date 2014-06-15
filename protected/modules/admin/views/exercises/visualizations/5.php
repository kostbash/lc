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

<div class="row" id="pick-blocks">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-2 col-md-2">
        <div class="errorMessage" id="errorCorrectAnswer"></div>
        <?php echo CHtml::link('Добавить вариант', '#', array('class'=>'btn btn-success', 'id'=>'add-variant')); ?>
    </div>
    <?php echo CHtml::hiddenField($id_right ? "Exercises[answers][$id_right][is_right]" : "", 1, array('id'=>'right_answer_hidden')); ?>
</div>
