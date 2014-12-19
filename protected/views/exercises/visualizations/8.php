<?php 
$text = $model->Questions[0]->text;
$spaces = $model->getSpaces('DESC');
$rightAnswers = $model->rightAnswers;
shuffle($rightAnswers);
if($text && $spaces)
{
    foreach($spaces as $space)
    {
        $html = "<div class='answer-droppable'><input class='hidden-answer' type='hidden' name='Exercises[$key][answers][number_spaces][]' value='$space' /></div>";
        $text = preg_replace("/sp{$space}/ui", $html, $text);
    }             
}
?>
        
<div class="text-with-space clearfix">
    <div class="text">
        <?php echo GraphicWidgets::transform($text); ?>
    </div>
    <div class="words">
        <?php foreach($rightAnswers as $answer) : ?>
        <div class="word"><input class='hidden-answer' type="hidden" name="Exercises[<?php echo $key; ?>][answers][answer][]" value="<?php echo $answer->answer; ?>" /><?php echo $answer->answer; ?></div>
        <?php endforeach; ?>
    </div>
</div>