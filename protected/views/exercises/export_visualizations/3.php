<div class="answer-head">Ответ :</div>
<div class="radio-buttons">
    <?php foreach($model->Answers as $answer) : ?>
        <div class="variant clearfix">
            <div class="radio-cont">
                <div class="radio"></div>
            </div>
            <div class='text'><?php echo $answer->answer; ?></div>
        </div>
    <?php endforeach; ?>
    
    <?php if($with_right) : ?>
        <div class='right-answer'>
            <?php
                $rightAnswers = array();
                foreach($model->rightAnswers as $answer)
                {
                    $rightAnswers[] = "\"$answer->answer\"";
                }
                echo "<b>Правильный ответ: </b>".implode(', ', $rightAnswers);
            ?>
        </div>
    <?php endif; ?>
</div>