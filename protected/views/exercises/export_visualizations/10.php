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
        $html = "<textarea cols='$width' class='textarea'></textarea>";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>

<div class="answer-head">Ответ :</div>
<div class="exact-answers-with-space clearfix">
    <div class="text">
        <?php echo $text; ?>
    </div>
</div>