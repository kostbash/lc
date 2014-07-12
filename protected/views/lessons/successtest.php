<script type="text/javascript">
    $(function() {
        $('#skills').popover();
    });
</script>
<div class="pass-lesson">
<div class="header-lesson clearfix">
    <div class="row lesson-header">
        <div class="names col-lg-9 col-md-9">
            <?php
                $contentSkills = "<table class='table table-hover' style='text-align: center'>
                    <thead>
                        <tr>
                            <th style='text-align: center'>Умение</th>
                            <th style='text-align: center'>Результат</th>
                        </tr>
                    </thead>
                    <tbody>";
                        if($userLesson->Lesson->Skills) {
                            foreach($userLesson->Lesson->Skills as $lessonSkill) {
                                $contentSkills .= "
                                    <tr>
                                        <td>$lessonSkill->name</td>
                                        <td>" . MyWidgets::ProgressBarWithLimiter(Lessons::PercentNeedBySkill($userLesson->id_lesson, $lessonSkill->id), Lessons::ProgressSkill($userLesson->id, $lessonSkill->id)) . "</td>
                                    </tr>";
                            }
                        } else {
                                $contentSkills .= "<tr><td colspan='2'>Нет умений</td></tr>";
                        }
                    $contentSkills .= "</tbody></table>";
            ?>
            <h1><?php echo "Урок $userLesson->position: ".$userLesson->Lesson->name; ?> <i style="font-size:16px;" id="skills" class="glyphicon glyphicon-tasks" data-toggle="popover" data-trigger ="hover" data-title = "Умения" data-html = 'true' data-container=".header-lesson" data-placement="bottom" data-content="<?php echo $contentSkills; ?>">
            </i></h1>
            <?php echo CHtml::link("Курс: ".$userLesson->Course->name, array('courses/index', 'id'=>$userLesson->Course->id)); ?>
        </div>
        <div class="next col-lg-3 col-md-3">
            <?php if($userLesson->Lesson->accessNextLesson($userLesson->id)) echo CHtml::link('Следующий урок<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'btn btn-success btn-icon-right')); ?>
        </div>
    </div>
</div>
<div class="row" style="position: relative">
    <div class="col-lg-7 col-md-7 exercises">
        <div class="widget">
        <?php $resultText = $userAndExerciseGroup->passed ? 'Вы успешно прошли тест' : 'Тест не пройден'; ?>
        <h2><?php echo $resultText; ?>. Результаты теста:</h2>

        <div class="skills ">
            <h2>Отрабатываемые умения:</h2>
            <table class="table table-hover" style="text-align: center">
                <thead>
                    <tr>
                        <th style="text-align: center">Умение</th>
                        <th style="text-align: center">Результат</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($resultTest) : ?>
                        <?php foreach($resultTest as $id_skill => $skillMass) : ?>
                            <tr>
                                <td><?php echo $skillMass[skill]->name ?></td>
                                <td><?php echo MyWidgets::ProgressBarWithLimiter($skillMass['need'], $skillMass['achieved']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                            <tr><td colspan="2">Нет умений</td></tr>
                    <?php endif; ?>
                </tbody>      
            </table>
        </div>
        <div style="text-align: center">
            <?php echo CHtml::link('Пройти еще раз<i class="glyphicon glyphicon-repeat"></i>', array('lessons/pass', 'id'=>$userLesson->id, 'group'=>$userAndExerciseGroup->id_exercise_group), array('class'=>'btn btn-primary btn-icon-right')); ?>
            <?php if($userAndExerciseGroup->passed) : ?>
                <?php echo $userAndExerciseGroup->getNextButton(); ?>
            <?php endif; ?>
        </div>
        </div>
    </div>
    
    <div class="col-lg-5 col-md-5 lesson-info">
        <div class="widget">
             <div class="list-exercises">
                <h2>Блоки урока</h2>
                <ul>
                <?php if($userLesson->Lesson->ExercisesGroups) : ?>
                    <?php foreach($userLesson->Lesson->ExercisesGroups as $pos => $group) : ++$pos; ?>
                        <?php if(UserAndExerciseGroups::ExistUserAndGroup($userLesson->id, $group->id)) : ?>
                            <?php
                                if($exerciseGroup->id == $group->id)
                                {
                                    $class = 'link current';
                                    $currentPos = $pos;
                                } else {
                                    $class = 'link';
                                }
                            ?>
                            <li><?php if($group->type==2) echo 'Тест: '; echo CHtml::link("$pos. $group->name", array('lessons/pass', 'id'=>$userLesson->id, 'group'=>$group->id), array('class'=>$class)); ?></li>
                        <?php else : ?>
                                <li><?php if($group->type==2) echo 'Тест: '; echo "$pos. $group->name"; ?></li>
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
