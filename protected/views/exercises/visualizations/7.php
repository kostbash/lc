<?php 
$list = array();

if(!empty($model->Answers))
{
    $listWords = explode(' ', trim($model->Answers[0]->answer));
    
    foreach($listWords as $listWord)
    {
        $list[] = "<li class='word'>"
                    ."$listWord"
                    ."<input class='hidden-answer' type='hidden' name='Exercises[$key][answers][]' value='$listWord' />"
                  ."</li>";
    }             
    // перемешиваем массивы
    shuffle($list);
}
?>
        
<div class="orderings clearfix">
     <ul>
        <?php echo implode('', $list); ?>
    </ul>
</div>