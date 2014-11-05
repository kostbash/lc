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
        <h3 class="skill-header">Умения</h3>
        <div class="block-skills">
            <?php
                if($skills = $block->GroupAndSkills)
                {
                    foreach($skills as $numerSkill => $skill)
                    {
                        $numerSkill++;
                        echo "<div class='block-skill'>$numerSkill. {$skill->Skill->name}</div>";
                    }
                }
                else
                {
                    echo "<div class='block-skill'>Нет умений</div>";
                }
            ?>
        </div>

        <h3 class="exercise-header">Задания</h3>
        <?php if ($exercises) : $posExercise = 0; ?>
            <div class="exercises">
                <?php foreach ($exercises as $i => $exercise) : $posExercise++; ?>
                    <div class="exercise clearfix <?php echo ( ++$i % 2) == 0 ? 'even' : 'odd'; ?>">
                        <div class="head clearfix">
                            <div class="number"><?php echo "$posExercise. "; ?></div>
                            <div class="condition"><?php echo $exercise->condition; ?></div>
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
