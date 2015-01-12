<div class="checkboxes">
    <?php $n = -1; ?>
    <?php foreach($model->Answers as $answer) : $n++; ?>
        <div class="checkbox">
            <?php
                echo "<input tabindex='$index' value='$answer->id' id='Exercises_{$key}_answers_{$n}' type='checkbox' name='Exercises[$key][answers][]'>";
                $gw = new GraphicWidgets($answer->answer);
                echo "<label for='Exercises_{$key}_answers_{$n}'><div class='left'><span></span></div><div class='right'>". $gw->draw() ."</div></label>";
            ?>
        </div>
    <?php endforeach; ?>
</div>