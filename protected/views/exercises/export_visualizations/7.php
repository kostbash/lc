<?php 
$list = array();

if(!empty($model->Answers))
{
    $listWords = explode(' ', trim($model->Answers[0]->answer));
    $rightList = array();
    foreach($listWords as $listWord)
    {
        $clearListWord = preg_replace('/_/u',' ',$listWord);
        $rightList[] = $clearListWord;
        $list[] = "\"$clearListWord\"";
    }             
    // перемешиваем массивы
    $rightAnswer = implode(' ', $rightList);
    shuffle($list);
}
?>
        
<div class="orderings clearfix">
    <b>Слова :</b> <?php echo implode(', ', $list); ?>
    <div><input class="full-field" type="text" /></div>
</div>

<?php if($with_right) : ?>
<div class='right-answer'>
    <?php
        echo "<b>Правильный ответ: </b>$rightAnswer";
    ?>
</div>
<?php endif; ?>