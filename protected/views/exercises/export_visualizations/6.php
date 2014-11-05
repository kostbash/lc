<?php 
$listOne = array();
$listTwo = array();
$countComparisons = count($model->Comparisons);
if($countComparisons)
{
    foreach($model->Comparisons as $comparison)
    {
        $listOne[] = "<div class='comp-answer-left'>{$comparison->AnswerOne->answer}</div>";
        $listTwo[] = "<div class='comp-answer-right'>{$comparison->AnswerTwo->answer}</div>";
    }
    
    $rightListOne = $listOne;
    $rightListTwo = $listTwo;
    
    // перемешиваем массивы
    shuffle($listOne);
    shuffle($listTwo);
}
?>

<div class="answer-head">Ответ :</div>
<div class="comparisons clearfix">
    <?php for($i=0; $countComparisons > $i; $i++) : ?>
        <div class='comparison clearfix'>
            <?php echo $listOne[$i]; ?>
            <?php echo $listTwo[$i]; ?>
        </div>
    <?php endfor; ?>
    
    <?php if($with_right) : ?>
        <div class='right-answer'>
            <div class="right-head">Правильный ответ: </div>
            <?php for($i=0; $countComparisons > $i; $i++) : ?>
                <div class='comparison clearfix'>
                    <?php echo $rightListOne[$i]; ?>
                    <?php echo $rightListTwo[$i]; ?>
                </div>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>