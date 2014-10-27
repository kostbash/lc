<?php if(!$pdf) : ?>
    <script>
        window.print();
    </script>
<?php endif; ?>

<div id="empty-layout">
    <div class='course'>
        <h2 class='course-name'>Курс: <?php echo $course->name; ?></h2>
        <table id="course-info">
            <thead>
                <tr>
                    <th>Пройдено</th>
                    <th>Средняя оценка</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $course->countPassedLessons; ?> <span>из</span> <?php echo $course->countLessons; ?></td>
                    <td><?php echo $course->averageByTests; ?> <span>%</span></td>
                </tr>
            </tbody>
        </table>

        <?php if($course->LessonsGroups) : $lessonContainerContent = ''; ?>
            <?php foreach ($course->LessonsGroups as $groupNum => $lessonGroup) : ++$groupNum; ?>
                <h3 class='theme-header'><?php echo "Тема $groupNum: \"$lessonGroup->name\""; ?></h3>
                <table class="lessons">
                    <tbody>
                        <?php if ($lessonGroup->LessonsRaw) : ?>
                            <?php foreach ($lessonGroup->LessonsRaw as $keyLesson => $lesson) : if($groupNum==1 && $keyLesson == 0 && $pos==1) continue; ?>
                                <tr>
                                    <td><?php echo "Урок $pos : \"$lesson->name\""; ?></td>
                                </tr>
                                <?php 
                                    $lessonContainerContent .= $this->renderPartial("//lessons/export_lesson", array('lesson'=>$lesson, 'pos'=>$pos, 'inner'=>true, 'with_right'=>$with_right), true);
                                    $pos++;
                                ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td>Нет уроков</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
            <div class="lessons-container">
                <?php echo $lessonContainerContent; ?>
            </div>
        <?php else : ?>
            <h3>Нет тем</h3>
        <?php endif; ?>
    </div>
 </div>