<div class="pick-blocks">
    <?php $n = -1; ?>
    <?php foreach($model->Answers as $answer) : $n++; ?>
        <div>
            <?php
                echo "<input tabindex='$index' value='$answer->id' id='Exercises_{$key}_answers_{$n}' type='checkbox' name='Exercises[$key][answers][]'>";
                echo "<label for='Exercises_{$key}_answers_{$n}'><span></span>$answer->answer</label>";
            ?>
        </div>
    <?php endforeach; ?>
</div>

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