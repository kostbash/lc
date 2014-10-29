<?php 
$text = $model->Questions[0]->text;
$rightText = $text;
$spaces = $model->spaces;
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $html = "<input type='text' />";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
        if($answer = ExercisesListOfAnswers::model()->findByAttributes(array('number_space'=>$space, 'id_exercise'=>$model->id, 'is_right'=>1)))
        {
            $rightText = preg_replace("/sp{$space}/ui", "<b>$answer->answer</b>", $rightText);
        }
        
    }             
}
?>

<div class="text-with-limits clearfix">
    <?php echo $text; ?>
</div>
<div class="words">
    Варианты ответа:
        <?php foreach($spaces as $n => $space) : $n++;
            $answers = $model->AnswersBySpace($space);
            $answersBySpace = array();
            foreach($answers as $answer)
            {
                $answersBySpace[] = "\"$answer->answer\"";
            }
        ?>
        <div class="word"><?php echo "Пробел $n : "; echo implode(", ", $answersBySpace);?></div>
        <?php endforeach; ?>
</div>

<?php if($with_right) : ?>
<div class='right-answer'>
    <?php
        echo "<b>Правильный ответ: </b>".$rightText;
    ?>
</div>
<?php endif; ?>