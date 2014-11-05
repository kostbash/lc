<div class="hotmap-ordering">
    <?php
        $rightAnswers = $model->rightAnswers;
        $shuffleRights = $rightAnswers;
        shuffle($shuffleRights);
        
    ?>
    
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
    </div>
    
    <div class="answer-head">Ответ :</div>
    
    <div class="items">
        <?php
            foreach($shuffleRights as $shuffleRight)
            {
                echo "<div class='item'>$shuffleRight->name</div>";
            }
        ?>
    </div>
    <?php if($with_right) : ?>
        <div class='right-answer'>
                <b>Правильный ответ :</b>
                <?php
                    foreach($rightAnswers as $n => $rightAnswer)
                    {
                        $n++;
                        echo "<div class='item'>$n. $rightAnswer->name</div>";
                    }
                ?>
        </div>
    <?php endif; ?>
</div>