<div class="pick-area">
    <img src="<?php echo $model->Map->mapImageLink; ?>" />
    <?php $imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
    <?php echo CHtml::hiddenField("Exercises[$key][answers]", '', array('id'=>false, 'class'=>'hidden-answer', 'data-key'=>$key)); ?>
</div>