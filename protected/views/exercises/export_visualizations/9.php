<?php 
$text = $model->Questions[0]->text;
$spaces = $model->spaces;
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $html = "<input type='text' />";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>

<div class="text-with-limits clearfix">
    <?php echo $text; ?>
</div>
<div class="words">
    Варианты ответа:
        <?php foreach($spaces as $n => $space) : 
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