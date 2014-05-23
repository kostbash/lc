<div class="row" style="position: relative">
    <div class="col-lg-12 col-md-12 course-info">
        <div class="header clearfix">
            <div class="left-block">
                <h1>Курс: <?php echo $course->name; ?></h1>
                <div class="desc">На этой странице отображены темы курса и уроки каждой темы. Чтобы освоить курс пройдите все уроки. Справа от каждого урока отображается Ваш прогресс по этому уроку.</div>
                <div class="pull-left"><?php echo $course->stateButton(); ?></div>
            </div>
            <div class="right-block">
                <?php $courseProgress = $course->progress; ?>
                <div class="progress progress-striped active">
                    <div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="<?php echo $courseProgress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $courseProgress ?>%">
                        <?php echo $courseProgress == 100 ? 'Курс завершен' : $courseProgress . "%"; ?>
                    </div>
                </div>
                <div>Всего уроков: <?php echo $course->getCountLessons(); ?></div>
            </div>
        </div>
        <div class="lessons">
            <!--h2>Уроки курса</h2-->
            <?php if ($course->LessonsGroups) : ?>
                <?php foreach ($course->LessonsGroups as $groupNum => $lessonGroup) : ++$groupNum; ?>
                    <h3><?php echo "Тема $groupNum: $lessonGroup->name"; ?></h3>
                    <?php if ($lessonGroup->LessonsRaw) : ?>
                        <table style="width: 100%;" class="table table-bordered table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Название урока</th>
                                    <th style="text-align: center; width:1%;">Пройден</th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($lessonGroup->LessonsRaw as $keyLesson => $lesson) :
                                if($keyLesson == 0 && $pos==1)
                                    continue;
                                $userAndLesson = UserAndLessons::existLesson($course->id, $lessonGroup->id, $lesson->id);
                                $popover_content = "<b>Умения:</b><br />";

                                if($userAndLesson) {
                                    foreach ($userAndLesson->Lesson->Skills as $lessonSkill)
                                        $popover_content .="<small>$lessonSkill->name: требуется: " . Lessons::PercentNeedBySkill($userAndLesson->id_lesson, $lessonSkill->id) . "%</small><br />";

                                    $popover_content .= "<br /><b>Задания:</b><br />";

                                    if ($userAndLesson->Lesson->ExercisesGroups) {
                                        foreach ($userAndLesson->Lesson->ExercisesGroups as $poss => $group) {
                                            ++$poss;
                                            if (UserAndExerciseGroups::ExistUserAndGroup($userAndLesson->id, $group->id)) {
                                                $class = $exerciseGroup->id == $group->id ? 'link current' : 'link';
                                                $popover_content .= "<small>" . "$poss. $group->nameType: $group->name</small><br />";
                                            } else {
                                                $popover_content .= "<small>" . "$poss. $group->nameType: $group->name " . "</small><br />";
                                            }
                                        }
                                    } else {
                                        $popover_content .="<small>Нет заданий</small><br />";
                                    }
                                }
                                $popover_content .= "";
                                if ($userAndLesson) :
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            
                                            $class = $userAndLesson->id_group == $lessonGroup->id && $userAndLesson->id_lesson == $lesson->id ? 'link current' : 'link';
                                            ?>
                                            <?php echo CHtml::link("Урок $pos : " . $lesson->name . " (" . Lessons::PercentRightTests($course->id, $lessonGroup->id, $lesson->id) . "%)", array('lessons/pass', 'id' => $userAndLesson->id), array('class' => $class . ' popover-element', 'data-placement' => "top", 'data-toggle' => "popover", 'data-trigger' => "hover", 'data-title' => "Урок", 'data-html' => 'true', 'data-content' => $popover_content)); ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($userAndLesson->LessonProgress >= 100) {
                                                ?><i class="glyphicon glyphicon-ok"></i><?php
                                            } else {
                                                echo $userAndLesson->LessonProgress . "%";
                                            }
                                            ?>
                                            <?php echo CHtml::link($userAndLesson->repeatLesson ? '<i class="glyphicon glyphicon-repeat" title="Повторить"></i>' : '<i class="glyphicon glyphicon-play" title="Продолжить"></i>', array('lessons/pass', 'id' => $userAndLesson->id)/* , array('class'=>'btn btn-primary btn-xs') */); ?>
                                        </td>
                                    </tr>

                                <?php else : ?>
                                    <tr><td><?php echo CHtml::tag('span', array()/*array('class' => $class . ' popover-element', 'data-placement' => "top", 'data-toggle' => "popover", 'data-trigger' => "hover", 'data-title' => "Урок", 'data-html' => 'true', 'data-content' => $popover_content)*/, "Урок $pos : $lesson->name") ; ?></td><td style="text-align: center;">0%</td></td>
                                <?php endif; ?>
                            <?php  ++$pos; ?>
                            <?php endforeach; ?>
                        </table>
                    <?php else : ?>
                        <ul><li>Нет уроков</li></ul>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                Нет уроков
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    $('.popover-element').popover();
</script>