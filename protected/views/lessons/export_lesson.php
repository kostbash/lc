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

        <table class="blocks">
            <tbody>
                <?php if($lesson->ExercisesGroups) : ?>
                    <?php foreach($lesson->ExercisesGroups as $pos => $group) : ++$pos; ?>
                        <tr>
                            <td><?php echo "$pos. $group->name"; if($group->type==2) echo ' <span class="type-block">тест !</span>'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td>Нет блоков</td></tr>
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
