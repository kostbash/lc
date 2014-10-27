<?php 
$text = $model->Questions[0]->text;
$spaces = $model->getSpaces('DESC');
$rightAnswers = $model->rightAnswers;
shuffle($rightAnswers);
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $html = "<input type='text' />";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
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