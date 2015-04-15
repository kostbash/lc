<?php
$this->renderPartial("//lessons/blocks/_2_scripts");
?>

<?php if($tasks) : $position=0; ?>
    <div id="exercises">
        <?php foreach($tasks as $key => $exercise) : $position++; ?>
            <div id="exercise_<?php echo $key; ?>" class="exercise <?php echo (++$i%2)==0 ? 'even' : 'odd'; ?>">
                <div class="head clearfix">
                    <div class="number"><?php echo $position; ?></div>
                    <div class="condition">
                        <?php $gw = new GraphicWidgets($exercise->condition);?>
                        <?php echo $gw->draw(); ?>
                    </div>
                </div>
                <div class="answer clearfix">
                    <?php if($exercise->id_visual) : ?>
                        <?=nl2br($this->renderPartial("//exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$key, 'index'=>$key+1))); ?>
                    <?php endif; ?>
                </div>
                <input class="duration" type="hidden" name="Exercises[<?php echo $key; ?>][duration]" value="0" />
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    Нет заданий
<?php endif; ?>
<div class="control-buttons">
    <?php echo CHtml::link('Проверить результаты', '#', array('class'=>'send-result-button', 'tabindex'=>$key+2)); ?>
</div>
