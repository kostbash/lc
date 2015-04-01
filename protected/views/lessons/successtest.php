<script type="text/javascript">
    $(function() {
        $('#skills').popover();
    });
</script>
<?php unset($skills['fail_pass'])?>

    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head"><?php echo !$fail ? 'Вы успешно прошли тест' : 'Тест не пройден'; ?></div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="lesson-page">
        <div id="result-test-page">
            <h2 class="block-name"><?php echo "РЕЗУЛЬТАТЫ ТЕСТА \"$test_name\""; ?></h2>
            <table id="skills-table">
                <thead>
                    <tr>
                        <th class="name-head">Название умения</th>
                        <th class="percent-head">Результат</th>
                        <th class="result-head">Итог</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($skills) : $i=0; ?>
                        <?php foreach($skills as $id_skill => $skillMass) : ++$i; ?>
                            <tr>
                                <td class="name-cont">
                                    <div class="number"><?php echo $i; ?></div>
                                    <div class="name"><?php echo $skillMass['name'] ?></div>
                                </td>
                                <td class="percent"><?php echo MyWidgets::ProgressBarWithLimiter($skillMass['need'], $skillMass['achieved']); ?></td>
                                <?php if($skillMass['passed']) : ?>
                                    <td class="result-skills pass">ПРОЙДЕНО!</td>
                                <?php else : ?>
                                    <td class="result-skills not-pass">НЕ ПРОЙДЕНО</td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                            <tr><td colspan="3">Нет умений</td></tr>
                    <?php endif; ?>
                </tbody>      
            </table>
            <div id="bottom">
                <?php if(!$fail) : ?>
                    <?php echo CHtml::link('К следующему блоку', array('lessons/nextblock', 'id'=>$id_course), array('tabindex'=>count($tasks)+1, 'class'=>'next-button')); ?>
                <?php endif; ?>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-refresh"></i>Пройти еще раз', array('lessons/newLesson', 'id'=>$id_course), array('class'=>'repeat-button')); ?>
            </div>
        </div>
    </div>
</div>
