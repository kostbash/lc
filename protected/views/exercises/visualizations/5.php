<div class="pick-blocks">
    <?php
    $i = 1;
    $next = 1;  
    if(!empty($model->Answers))
    {
        foreach($model->Answers as $answer)
        {
            if($i==$next)
            {
                $additionClass = ' first-in-line';
                $next += 3;
            }
            else
            {
                $additionClass = '';
            }
            echo "<div data-key='$key' data-val='$answer->id' class='block$additionClass'>".GraphicWidgets::transform($answer->answer)."</div>";
            ++$i;
        }
    }
    echo CHtml::hiddenField("Exercises[$key][answers]", '', array('id'=>false, 'class'=>'hidden-answer', 'data-key'=>$key));
    ?>
</div>