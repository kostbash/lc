<div class="answer-head">Ответ :</div>
<div class="accurate-answer">
    <div class="full-field"></div>
    <?php if($with_right) : ?>
    <div class='right-answer'>
        <?php
            $rightAnswers = array();
            foreach($model->rightAnswers as $answer)
            {
                $rightAnswers[] = "\"$answer->answer\"";
            }
            echo "<b>Правильные ответы: </b>".implode(', ', $rightAnswers);
        ?>
    </div>
    <?php endif; ?>
</div>