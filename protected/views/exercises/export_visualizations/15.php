<div class="hotmap-ordering">
    <?php
        $rightAnswers = $model->rightAnswers;
        $shuffleRights = $rightAnswers;
        shuffle($shuffleRights);
        
    ?>
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
        <?php $imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
    </div>
    <div class="items">
        <b>Области :</b>
        <?php
            foreach($shuffleRights as $shuffleRight)
            {
                echo "<div>$shuffleRight->name</div>";
            }
        ?>
    </div>
</div>

<?php if($with_right) : ?>
<div class='right-answer'>
        <b>Правильный ответ :</b>
        <?php
            foreach($rightAnswers as $n => $rightAnswer)
            {
                $n++;
                echo "<div>$n. $rightAnswer->name</div>";
            }
        ?>
</div>
<?php endif; ?>