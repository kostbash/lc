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
        <table class="skill">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Проходной процент</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($skills = $block->GroupAndSkills)
                    {
                        foreach($skills as $skill)
                        {
                            echo "<tr>";
                                echo "<td>{$skill->Skill->name}</td>";
                                echo "<td>$skill->nicePercent%</td>";
                            echo "</tr>";
                        }
                    }
                    else
                    {
                        echo '<tr><td colspan="2">Нет умений</td></tr>';
                    }
                ?>
            </tbody>
        </table>

        <h3 class="exercise-header">Задания</h3>
        <?php if ($exercises) : $posTest = 1; ?>
            <table class="exercises">
                <?php foreach ($exercises as $i => $exercise) : ?>
                    <tr class="exercise <?php echo ( ++$i % 2) == 0 ? 'even' : 'odd'; ?>">
                        <td class="exercise-main-td">
                            <table>
                                <tr>
                                    <td class="number">
                                        <?php echo "Номер: ". $posTest++; ?>
                                    </td>
                                    <td class="condition">
                                        <?php echo $exercise->condition; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="answer" colspan="2">
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
