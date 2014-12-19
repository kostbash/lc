<?php if(!($inner && $pdf)) : ?>
    <script>
        window.print();
    </script>
<?php endif; ?>

<?php if(!$inner) : ?>
<div id="empty-layout">
<?php endif; ?>
    <div class="block">
        <h2 class="block-name"><?php echo "Блок $pos: \"$block->name\""; ?></h2>
        <div class="block-skills">
            <b>Умения: </b>
            <?php
                $blockSkills = array();
                if($skills = $block->GroupAndSkills)
                {
                    foreach($skills as $numerSkill => $skill)
                    {
                        $blockSkills[] = $skill->Skill->name;
                    }
                }
                else
                {
                    $blockSkills[] = "Нет умений";
                }
                echo implode('; ', $blockSkills);
            ?>
        </div>

        <?php if ($exercises) : $posExercise = 0; ?>
            <div class="exercises">
                <?php foreach ($exercises as $i => $exercise) : $posExercise++; ?>
                    <div class="exercise clearfix <?php echo ( ++$i % 2) == 0 ? 'even' : 'odd'; ?>">
                        <div class="head clearfix">
                            <div class="number"><?php echo "$posExercise. "; ?></div>
                            <div class="condition">
                                <?php echo GraphicWidgets::transform($exercise->condition);?>
                            </div>
                        </div>
                        <div class="answer">
                            <?php if ($exercise->id_visual) : ?>
                                <?php $this->renderPartial("//exercises/export_visualizations/{$exercise->id_visual}", array('model' => $exercise, 'with_right'=>$with_right)); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class='not-exercises'>Нет заданий</div>
        <?php endif; ?>
    </div>
<?php if(!$inner) : ?>
</div>
<?php endif; ?>
