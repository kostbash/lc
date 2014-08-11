<?php 
$text = $model->Questions[0]->text;
$spaces = $model->spaces;
if($text && $spaces)
{
    foreach($spaces as $key => $space)
    {
        $answersBySpace = $model->AnswersBySpace($space);
        $html = CHtml::dropDownList("", $answers[$key+1], CHtml::listData($answersBySpace, 'id', 'answer'), array('id'=>false));
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>

<div class="orderings clearfix">
     <ul>
        <?php echo $text; ?>
    </ul>
</div>