<?php 
$text = $model->Questions[0]->text;
$spaces = $model->getSpaces('DESC');
$rightAnswers = $model->rightAnswers;
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $answer = ExercisesListOfAnswers::model()->findByAttributes(array('id_exercise'=>$model->id, 'number_space'=>$space));
        $answerLength = mb_strlen($answer->answer, 'UTF-8');
        $width = $answerLength > 6 ? $answerLength : 6;
        $html = "<input class='' size='$width' type='text' name='Exercises[$key][answers][number_spaces][$space]' />";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>
        
<div class="exact-answers-with-space clearfix">
    <div class="text">
        <?php echo nl2br($text); ?>
    </div>
</div>