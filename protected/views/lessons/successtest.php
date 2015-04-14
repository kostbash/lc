<script type="text/javascript">
    $(function() {
        $('#skills').popover();
    });
</script>

    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head"><?php echo $resultTest['passed'] ? 'Вы успешно прошли тест' : 'Тест не пройден'; ?></div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="lesson-page">
        <div id="result-test-page">
            <h2 class="block-name"><?php echo "РЕЗУЛЬТАТЫ ТЕСТА \"$exerciseGroup->name\""; ?></h2>
            <table id="skills-table">
                <thead>
                    <tr>
                        <th class="name-head">Название умения</th>
                        <th class="percent-head">Результат</th>
                        <th class="result-head">Итог</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($resultTest['skills']) : $i=0; ?>
                        <?php foreach($resultTest['skills'] as $id_skill => $skillMass) : ++$i; ?>
                            <tr>
                                <td class="name-cont">
                                    <div class="number"><?php echo $i; ?></div>
                                    <div class="name"><?php echo $skillMass[skill]->name ?></div>
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
                <?php if($userAndExerciseGroup->passed) : ?>
                    <?php echo $userAndExerciseGroup->getNextButton(); ?>
                <?php endif; ?>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-refresh"></i>Пройти еще раз', array('lessons/pass', 'id'=>$userLesson->id, 'group'=>$userAndExerciseGroup->id_exercise_group), array('class'=>'repeat-button')); ?>
            </div>
        </div>
    </div>
</div>
