<div class="accurate-answer">
    <div class='field'>                                                             </div>
</div>

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