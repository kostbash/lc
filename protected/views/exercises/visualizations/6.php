<?php 
$listOne = array();
$listTwo = array();
$countComparisons = count($model->Comparisons);
if($countComparisons)
{
    foreach($model->Comparisons as $comparison)
    {
        $listOne[] = "<div class='comp-answer'>".GraphicWidgets::transform($comparison->AnswerOne->answer).
                     "<input class='hidden-answer' type='hidden' name='Exercises[$key][answers][1][]' value='{$comparison->AnswerOne->id}' /></div>";
        $listTwo[] = "<div class='comp-answer'>".GraphicWidgets::transform($comparison->AnswerTwo->answer).
                     "<input class='hidden-answer' type='hidden' name='Exercises[$key][answers][2][]' value='{$comparison->AnswerTwo->id}' /></div>";
    }
    
    // перемешиваем массивы
    shuffle($listOne);
    shuffle($listTwo);
}
?>
        
<div class="comparisons clearfix">
    <?php for($i=0; $countComparisons > $i; $i++) : ?>
        <div class='comparison clearfix'>
            <div class="list-one">
                <?php echo $listOne[$i]; ?>
            </div>
            <div class="list-two">
                <?php echo $listTwo[$i]; ?>
            </div>
        </div>
    <?php endfor; ?>
</div>