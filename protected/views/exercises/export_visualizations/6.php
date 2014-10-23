<?php 
$listOne = array();
$listTwo = array();
if(!empty($model->Comparisons))
{
    foreach($model->Comparisons as $comparison)
    {
        $listOne[] = "<div class='comparison'>".
                        "<div class='comp-answer'>{$comparison->AnswerOne->answer}</div>".
                        "<input class='hidden-answer' type='hidden' name='Exercises[$key][answers][1][]' value='{$comparison->AnswerOne->id}' />".
                    "</div>";
        $listTwo[] = "<div class='comparison'>".
                        "<div class='comp-answer'>{$comparison->AnswerTwo->answer}</div>".
                        "<input class='hidden-answer' type='hidden' name='Exercises[$key][answers][2][]' value='{$comparison->AnswerTwo->id}' />".
                    "</div>";
    }
    
    // перемешиваем массивы
    shuffle($listOne);
    shuffle($listTwo);
}
?>
        
<div class="comparisons clearfix">
    <div class="list-one">
        <?php echo implode('', $listOne); ?>
    </div>
    <div class="list-two">
        <?php echo implode('', $listTwo); ?>
    </div>
</div>