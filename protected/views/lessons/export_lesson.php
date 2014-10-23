<?php if(!($inner && $pdf)) : ?>
    <script>
        window.print();
    </script>
<?php endif; ?>

<?php if(!$inner) : ?>
<div id="empty-layout">
<?php endif; ?>
    <div class="lesson">
        <h2 class="lesson-name"><?php echo "Урок $pos: \"$lesson->name\""; ?></h2>
        <h3 class="skill-header">Умения</h3>
        <table class="skill">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Название</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($skills = $lesson->Skills)
                    {
                        $i = 1;
                        foreach($skills as $num => $skill)
                        {
                            echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td>{$skill->name}</td>";
                            echo "</tr>";
                            $i++;
                        }
                    }
                    else
                    {
                        echo '<tr><td colspan="2">Нет умений</td></tr>';
                    }
                ?>
            </tbody>
        </table>
        
        <h3 class="blocks-header">Блоки</h3>
        <table class="blocks">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Название</th>
                    <th>Тип</th>
                </tr>
            </thead>
            <tbody>
                <?php if($lesson->ExercisesGroups) : ?>
                    <?php foreach($lesson->ExercisesGroups as $pos => $group) : ++$pos; ?>
                        <tr>
                            <td><?php echo $pos; ?></td>
                            <td><?php echo $group->name; ?></td>
                            <td><?php echo $group->nameType; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="3">Нет блоков</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="blocks-container">
            <?php foreach($lesson->ExercisesGroups as $pos => $block) : ++$pos; ?>
                <?php $this->renderPartial("//lessons/export_block", array('block'=>$block, 'exercises'=> $block->type==1 ? $block->Exercises : $block->ExercisesTest, 'pos'=>$pos, 'with_right'=>$with_right, 'inner'=>true)); ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php if(!$inner) : ?>
</div>
<?php endif; ?>
