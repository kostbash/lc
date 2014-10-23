<div class="hotmap-ordering">
    <?php
        $rightAnswers = $model->rightAnswers;
    ?>
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
        <?php $imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
    </div>
    <div class="bag">
        <div class="head">Перенесите сюда</div>
        <div class="bag-drop">
            <div class="dropped-items"></div>
        </div>
    </div>
    <div class="items">
        <?php foreach($rightAnswers as $answer) : ?>
            <div class="item" data-area="<?php echo $answer->id_area; ?>">
                <input class='hidden-answer' type="hidden" name="Exercises[<?php echo $key; ?>][answers][<?php echo $answer->id; ?>]" value="" />
                <?php echo $answer->name; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>