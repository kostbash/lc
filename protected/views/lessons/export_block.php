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
        <table class="block-skills">
            <tbody>
                <?php
                    if($skills = $block->GroupAndSkills)
                    {
                        foreach($skills as $numerSkill => $skill)
                        {
                            $numerSkill++;
                            echo "<tr>";
                                echo "<td>$numerSkill.{$skill->Skill->name}</td>";
                            echo "</tr>";
                        }
                    }
                    else
                    {
                        echo '<tr><td>Нет умений</td></tr>';
                    }
                ?>
            </tbody>
        </table>

        <h3 class="exercise-header">Задания</h3>
        <?php if ($exercises) : $posExercise = 0; ?>
            <table class="exercises">
                <?php foreach ($exercises as $i => $exercise) : $posExercise++; ?>
                    <tr class="exercise <?php echo ( ++$i % 2) == 0 ? 'even' : 'odd'; ?>">
                        <td class="exercise-main-td">
                            <table>
                                <tr>
                                    <td class="condition">
                                        <?php echo "<span class='number'>$posExercise.</span> $exercise->condition"; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="answer">
                                        <div class="answer-head">Ответ :</div>
                                        <?php if ($exercise->id_visual) : ?>
                                            <?php $this->renderPartial("//exercises/export_visualizations/{$exercise->id_visual}", array('model' => $exercise, 'with_right'=>$with_right)); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <div class='not-exercises'>Нет заданий</div>
        <?php endif; ?>
    </div>
<?php if(!$inner) : ?>
</div>
<?php endif; ?>
