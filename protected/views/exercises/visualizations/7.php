<?php 
$list = array();

if(!empty($model->Answers))
{
    $listWords = explode(' ', trim($model->Answers[0]->answer));
    
    foreach($listWords as $listWord)
    {
        $clearListWord = preg_replace('/_/u',' ',$listWord);
        $list[] = "<div class='word'>"
                    ."$clearListWord"
                    ."<input class='hidden-answer' type='hidden' name='Exercises[$key][answers][]' value='$listWord' />"
                  ."</div>";
    }             
    // перемешиваем массивы
    shuffle($list);
}
?>
        
<div class="orderings clearfix">
    <?php echo implode('', $list); ?>
</div>