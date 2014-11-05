<div class="hotmap-items">
    <div class="items">
        <?php
            $rightAnswers = $model->rightAnswers;
            shuffle($rightAnswers);
            $clearRight = array();
        ?>
        <?php foreach($rightAnswers as $answer) : ?>
                <?php $clearRight[] = "\"$answer->name\""; ?>
        <?php endforeach; ?>
        <b>Предметы :</b> <?php echo implode(', ', $clearRight); ?>
    </div>
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
    </div>
</div>