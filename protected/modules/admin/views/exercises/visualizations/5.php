<?php
if($model->isNewRecord && !empty($model->Answers))
{
    foreach($model->Answers as $answer)
        $this->renderPartial('5_view_of_variant', array('model'=>$model, 'answer'=>$answer));
}
?>
<div class="row">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-2 col-md-2">
        <?php echo CHtml::link('Добавить вариант', '#', array('class'=>'btn btn-success', 'id'=>'add-editor-variant')); ?>
    </div>
</div>
