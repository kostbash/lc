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
                <div class="content">
                    <div id="lesson-page-header">
                        <div class="clearfix">
                            <div id="lesson-info">
                                <?php
                                    $contentSkills .= "<table>";
                                        if($userLesson->Lesson->Skills) {
                                            $numSkill = 1;
                                            foreach($userLesson->Lesson->Skills as $lessonSkill)
                                            {
                                                $contentSkills .= "<tr class='skill'>";
                                                    $contentSkills .= "<td class='number'>$numSkill</td>";
                                                    $contentSkills .= "<td class='name'>$lessonSkill->name</td>";
                                                $contentSkills .= "</tr>";
                                                ++$numSkill;
                                            }
                                        }
                                        else
                                        {
                                            $contentSkills .= "<tr class='skill no-skills'><td class='number'></td><td class='name'>Нет умений<td></tr>";
                                        }
                                        
                                    $contentSkills .= "</table>";
                                ?>
                                <div id="lesson-name">
                                    <?php echo "УРОК $userLesson->position: ".$userLesson->Lesson->name; ?>
                                    <div id="skills" data-toggle="popover" data-trigger ="hover" data-html='true' data-container="#lesson-name" data-placement="right" data-content="<?php echo $contentSkills; ?>"></div>
                                </div>
                                <?php echo CHtml::link('Название курса "'.$userLesson->Course->name.'"', array('courses/index', 'id'=>$userLesson->Course->id), array('id'=>'course-name')); ?>
                            </div>
                            <div id="next-lesson">
                                <?php 
                                    if( $userLesson->Lesson->accessNextLesson($userLesson->id) )
                                        echo CHtml::link('Следующий урок', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'next-lesson-button'));
                                ?>
                            </div>
                        </div>
                        <div id="blocks">
                            <h1>Блоки урока</h1>
                            <ul>
                                <?php if($userLesson->Lesson->ExercisesGroups) : ?>
                                    <?php foreach($userLesson->Lesson->ExercisesGroups as $pos => $group) : ++$pos; ?>
                                        <?php if(UserAndExerciseGroups::ExistUserAndGroup($userLesson->id, $group->id)) : ?>
                                            <?php
                                                if($exerciseGroup->id == $group->id)
                                                {
                                                    $class = 'current';
                                                    $currentPos = $pos;
                                                } else {
                                                    $class = '';
                                                }
                                            ?>
                                            <li>
                                                <?php
                                                    echo "$pos. ";
                                                    echo CHtml::link($group->name, array('lessons/pass', 'id'=>$userLesson->id, 'group'=>$group->id), array('class'=>$class));
                                                    if($group->type==2) echo '<span class="test">Тест!</span>'; 
                                                ?>
                                            </li>
                                        <?php else : ?>
                                            <li>
                                                <?php
                                                    echo "$pos. $group->name";
                                                    if($group->type==2) echo '<span class="test">Тест!</span>'; 
                                                ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li>Нет блоков</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<?php //$resultText = $resultTest['passed'] ? 'Вы успешно прошли тест' : 'Тест не пройден'; ?>
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
                                <?php if($skillMass['passsed']) : ?>
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
