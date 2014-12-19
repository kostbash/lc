<?php 
$text = $model->Questions[0]->text;
$spaces = $model->spaces;
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $answers = $model->AnswersBySpace($space);
        $html = CHtml::dropDownList("Exercises[$key][answers][$space]", '', CHtml::listData($answers, 'id', 'answer'), array('id'=>false, 'empty'=>'Выберите ответ'));
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>

<div class="text-with-limits clearfix">
    <?php echo GraphicWidgets::transform($text); ?>
</div>