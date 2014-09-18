    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-col-left" class="head-column">
                <div class="content">
                    <div class="course">
                        <div class="head">
                            <div class="info">
                                <div class="status current">Текущий курс:</div>
                                <div class="name"><?php echo $course->name; ?></div>
                            </div>
                            <?php echo $course->stateButton(); ?>
                        </div>
                        <div class="content clearfix">
                            <div class="passed-lessons">
                                <h4>Пройдено:</h4>
                                <div class="value"><?php echo $course->countPassedLessons; ?> <span>из</span> <?php echo $course->countLessons; ?></div>
                            </div>
                            <div class="average">
                                <h4>Средняя оценка:</h4>
                                <div class="value"><?php echo $course->averageByTests; ?> <span>%</span></div>
                            </div>
                        </div>
                        <div class="progress progress-striped active">
                            <?php $courseProgress = $course->progress; ?>
                            <div class="progress-bar progress-bar-warning"  role="progressbar" aria-valuenow="<?php echo $courseProgress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $courseProgress; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="head-col-right" class="head-column">
                <div class="content">
                    <div class="text">
                        <p>Место, чтобы инфу по курсу поместить</p>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id='course-page'>
        <?php if ($course->LessonsGroups) : ?>
            <?php foreach ($course->LessonsGroups as $groupNum => $lessonGroup) : ++$groupNum; ?>
                <h1 class='theme-name'><?php echo "Тема $groupNum: \"$lessonGroup->name\""; ?></h1>
                    <table class='lessons-table'>
                        <colgroup>
                         <col width="65%">
                         <col width="20%">
                         <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class='name'>Название урока</th>
                                <th colspan="2" class='status'>Состояние</th>
                            </tr>
                        </thead>
                        <?php if ($lessonGroup->LessonsRaw) : ?>
                            <?php foreach ($lessonGroup->LessonsRaw as $keyLesson => $lesson) : ?>
                                <?php if($groupNum==1 && $keyLesson == 0 && $pos==1) { continue; } ?>
                                <?php if($userAndLesson = UserAndLessons::existLesson($course->id, $lessonGroup->id, $lesson->id)) : ?>
                                    <tr>
                                        <td>
                                            <?php echo CHtml::link("Урок $pos : ".$lesson->name, array('lessons/pass', 'id' => $userAndLesson->id), array('class'=>'lesson-name')); ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($userAndLesson->LessonProgress == 100) : ?>
                                                <p class="passed">Пройден</p>
                                            <?php else : ?>
                                                <div class="percent">
                                                    <?php echo $userAndLesson->LessonProgress; ?>
                                                    <span>%</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php $imageName = $userAndLesson->repeatLesson ? 'repeat' : 'play'; ?>
                                            <?php echo CHtml::link("<img src='/images/$imageName.png' width='37' height='36' />", array('lessons/pass', 'id' => $userAndLesson->id), array('class'=>'to-lesson')); ?>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td><?php echo "Урок $pos : $lesson->name"; ?></td>
                                        <td style="text-align: center;">
                                            <p class="unaccess">Не пройден</p>
                                        </td> 
                                        <td style="text-align: center;"></td> 
                                    </tr>
                                <?php endif; ++$pos; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3">Нет уроков</td>
                            </tr>
                        <?php endif; ?>
                    </table>
            <?php endforeach; ?>
        <?php else : ?>
            Курс пуст
        <?php endif; ?>
    </div>
</div>