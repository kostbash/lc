<div class="answer-head">Ответ :</div>
<div class="pick-blocks">
    <?php foreach($model->Answers as $answer) : ?>
        <div class="variant clearfix">
            <div class="checkbox-cont">
                <div class="checkbox"></div>
            </div>
            <div class='text'><?php echo $answer->answer; ?></div>
        </div>
    <?php endforeach; ?>
    
    <?php if($with_right) : ?>
        <div class='right-answer'>
            <div class="right-head">Правильный ответ: </div>
            <?php
                $rightAnswers = array();
                foreach($model->rightAnswers as $answer)
                {
                    $rightAnswers[] = $answer->answer;
                }
                echo implode(', ', $rightAnswers);
            ?>
        </div>
    <?php endif; ?>
</div>