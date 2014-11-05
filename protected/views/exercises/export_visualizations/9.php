<?php 
$text = $model->Questions[0]->text;
$rightText = $text;
$spaces = $model->spaces;
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $html = "<textarea class='textarea'>$space.</textarea>";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
        if($answer = ExercisesListOfAnswers::model()->findByAttributes(array('number_space'=>$space, 'id_exercise'=>$model->id, 'is_right'=>1)))
        {
            $rightText = preg_replace("/sp{$space}/ui", "<b>$answer->answer</b>", $rightText);
        }
        
    }             
}
?>

<div class="text-with-limits clearfix">
    <div class="words">
        <b>Варианты ответа: </b>
        <?php foreach($spaces as $space) :
            $answers = $model->AnswersBySpace($space);
            $answersBySpace = array();
            foreach($answers as $answer)
            {
                $answersBySpace[] = "\"$answer->answer\"";
            }
        ?>
            <div class="space"><?php echo "Пробел $space : "; echo implode(", ", $answersBySpace);?></div>
        <?php endforeach; ?>
    </div>
    
    <div class="answer-head">Ответ :</div>
    <div class="text">
        <?php echo $text; ?>
    </div>
    
    <?php if($with_right) : ?>
        <div class='right-answer'>
            <?php
                echo "<b>Правильный ответ: </b>".$rightText;
            ?>
        </div>
    <?php endif; ?>
</div>