<?php 
$text = $model->Questions[0]->text;
$spaces = $model->getSpaces('DESC');
if($text && $spaces)
{
    $numberSpaces = count($spaces);
    foreach($spaces as $key => $space)
    {
        $html = "<div class='answer-droppable'>";
            $html .= "<div class='word'>";
                $html .= ExercisesListOfAnswers::model()->findByPk($answers['answer'][--$numberSpaces])->answer;
            $html .= "</div>";
        $html .= "</div>";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>
        
<div class="text-with-space clearfix">
    <div class="text">
        <?php echo $text; ?>
    </div>
</div>