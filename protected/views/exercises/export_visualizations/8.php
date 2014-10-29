<?php 
$text = $model->Questions[0]->text;
$rightText = $text;
$spaces = $model->getSpaces('DESC');
$rightAnswers = $model->rightAnswers;
shuffle($rightAnswers);
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $html = "<input type='text' />";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
        foreach($rightAnswers as $rightAnswer)
        {
            if($rightAnswer->number_space==$space)
            {
                $rightText = preg_replace("/sp{$space}/ui", "<b>$rightAnswer->answer</b>", $rightText);
            }
        }
    }             
}
?>
        
<div class="text-with-space clearfix">
    <div class="text">
        <?php echo $text; ?>
    </div>
    <div class="words">
        Варианты ответа:
        <?php foreach($rightAnswers as $n => $answer) : $n++;?>
        <div class="word"><?php echo  "$n.$answer->answer"; ?></div>
        <?php endforeach; ?>
    </div>
</div>

<?php if($with_right) : ?>
<div class='right-answer'>
    <?php
        $rightAnswers = array();
        foreach($model->rightAnswers as $answer)
        {
            $rightAnswers[] = "\"$answer->answer\"";
        }
        echo "<b>Правильный ответ: </b>".$rightText;
    ?>
</div>
<?php endif; ?>