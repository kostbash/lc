<?php

if(!($model->isNewRecord && empty($model->Comparisons)))
{
    $n = 0;
    foreach($model->Comparisons as $comparison)
    {
        $this->renderPartial('visualizations/6_variant', array('model'=>$model, 'comparison'=>$comparison, 'index'=>++$n));
    }
}
?>

<div class="row" id="comparisons">
    <div class="col-lg-offset-9 col-md-offset-9 col-lg-3 col-md-3">
        <div class="errorMessage"></div>
        <?php echo CHtml::link('Добавить сравнение', '#', array('class'=>'btn btn-success', 'id'=>'add-variant', 'style'=>'float: right')); ?>
    </div>
</div>
