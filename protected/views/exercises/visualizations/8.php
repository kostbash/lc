<?php 
$text = $model->Questions[0]->text;
$spaces = $model->spaces;
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $answers = $model->AnswersBySpace($space);
        $html = '<div class="answer-droppable"></div>';
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>
        
<div class="text-with-space clearfix">
    <div class="text">
        <?php echo $text; ?>
    </div>
    <div class="words">
        
    </div>
</div>