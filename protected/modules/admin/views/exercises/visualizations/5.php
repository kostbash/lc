<?php

if(!($model->isNewRecord && empty($model->Answers)))
{
    $n = 0;
    foreach($model->Answers as $answer)
    {
        $this->renderPartial('visualizations/5_editor_variant', array('model'=>$model, 'answer'=>$answer, 'index'=>++$n));
    }
}
?>

<div class="row" id="pick-blocks">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-2 col-md-2">
        <div class="errorMessage" id="errorCorrectAnswer"></div>
        <?php echo CHtml::link('Добавить вариант', '#', array('class'=>'btn btn-success', 'id'=>'add-editor-variant')); ?>
    </div>
</div>
