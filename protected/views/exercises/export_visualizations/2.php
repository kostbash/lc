<div class="dropdown-list">
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