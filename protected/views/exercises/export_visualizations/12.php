<div class="hotmap-items">
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
        <?php $imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
    </div>
    <div class="items">
        <?php
            $rightAnswers = $model->rightAnswers;
            shuffle($rightAnswers);
            $clearRight = array();
        ?>
        <?php foreach($rightAnswers as $answer) : ?>
                <?php $clearRight[] = "\"$answer->name\""; ?>
            </div>
        <?php endforeach; ?>
        Предметы : <?php echo implode(', ', $clearRight); ?>
    </div>
</div>