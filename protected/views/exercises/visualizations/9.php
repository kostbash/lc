<?php 
$text = $model->Questions[0]->text;
$spaces = $model->spaces;
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $answers = $model->AnswersBySpace($space);
        $html = CHtml::dropDownList("Exercises[$key][answers][$space]", '', CHtml::listData($answers, 'id', 'answer'), array('id'=>false));
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>
        
<div class="orderings clearfix">
     <ul>
        <?php echo $text; ?>
    </ul>
</div>