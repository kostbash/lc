<?php
    $this->pageTitle="$course->name. ".Yii::app()->name.".";
    $messages = SourceMessages::MessagesByCategories(array('course-pages'));
?>
    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js"); ?>
<script type="text/javascript">
    $(function(){
        $('.with-right').change(function(){
            current = $(this);
            links = current.closest('.export-button').find('.dropdown-menu li a');
            if(current.val()==1)
            {
                links.each(function(n, link){
                    link = $(link);
                    str = link.attr('href');
                    str = str.replace(/with_right=1/g, 'with_right=0');
                    link.attr('href', str);
                });
                current.val(0);
            }
            else
            {
                links.each(function(n, link){
                    link = $(link);
                    str = link.attr('href');
                    str = str.replace(/with_right=0/g, 'with_right=1');
                    link.attr('href', str);
                });
                current.val(1);
            }
        });
    });
</script>

    <div id="back-header-bottom">
        <div id="header-bottom">
                <div id="head-col-left" class="head-column">
                    <div class="content">
                        <div class="course">
                            <div class="head">
                                <div class="info">
                                    <div class="status current"><?php echo CHtml::link('Курсы', array('courses/list')); ?> <i class="glyphicon glyphicon-arrow-right" style="top:2px;"></i>Текущий курс:</div>
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
                            <?php if(!$courseUser->is_begin) : ?>
                                <?php echo CHtml::link(Yii::t('course-pages', $messages[12]->message), array('lessons/check', 'course'=>$course->id), array('class'=>'send-result-button')); ?>
                            <?php endif; ?>
                            <?php /* ?>
                            <div class="export-button">
                                <button type="button" class="dropdown-toggle" data-toggle="dropdown">Экспорт курса<span class="caret"></span></button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="<?php echo Yii::app()->createUrl('courses/print', array('id'=>$course->id, 'with_right'=>0)); ?>" target="_blank">Печать</a></li>
                                    <li><a href="<?php echo Yii::app()->createUrl('courses/toPdf', array('id'=>$course->id, 'with_right'=>0)); ?>" target="_blank">PDF</a></li>
                                </ul>
                                <?php if(Yii::app()->user->checkAccess('editor')) : ?>
                                    <div class='with-right-cont'>
                                        <input id='with-right-course' type='checkbox' class='with-right' name value='0' />
                                        <label for='with-right-course'>С ответами</label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php */ ?> 
                        </div>
                    </div>
                </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id='course-page'>
        <?php if(!$courseUser->is_begin) : ?>
            <div id="course-page-info" class="clearfix">
                <div class="get">
                    <div class="content">
                        <div class="info">
                            <div class="name"><?php echo Yii::t('course-pages', $messages[13]->message); ?></div>
                            <ul>
                                <?php if($course->NeedKnows) : ?>
                                    <?php foreach($course->NeedKnows as $needknow) : ?>
                                        <li><span><?php echo $needknow->name; ?></span></li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li><span>Ничего</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="right-background"></div>
                    </div>
                </div>
                <div class="need">
                    <div class="content">
                        <div class="info">
                            <div class="name"><?php echo Yii::t('course-pages', $messages[14]->message); ?></div>
                            <ul>
                                <?php if($course->YouGets) : ?>
                                    <?php foreach($course->YouGets as $youget) : ?>
                                        <li><span><?php echo $youget->name; ?></span></li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li><span>Ничего</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="right-background"></div>
                    </div>
                </div>
                <div class="statistic">
                    <div class="content">
                        <div class="info">
                            <div class="name"><?php echo Yii::t('course-pages', $messages[15]->message); ?></div>
                            <div class="param-cont clearfix">
                                <div class="param">Уроков: </div>
                                <div class="value"><?php echo $course->countLessons; ?></div>
                            </div>
                            <div class="param-cont clearfix">
                                <div class="param">Упражнений: </div>
                                <div class="value"><?php echo $course->getCountBlocks(); ?></div>
                            </div>
                            <div class="param-cont clearfix">
                                <div class="param">Тестов: </div>
                                <div class="value"><?php echo $course->getCountBlocks(2); ?></div>
                            </div>
                            <div class="param-cont clearfix">
                                <div class="param">Предполагаемое время обучения</div>
                                <div class="value"><?php echo $course->learning_time ? $course->learning_time : 'Не указано'; ?></div>
                            </div>
                        </div>
                        <div class="right-background"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
            if($newParent)
            {
                $this->renderPartial('//deals/_confirm_modal', array('newParent'=>$newParent));
            }
        ?> 
        <?php if ($course->LessonsGroups) : $posLesson = 1; $isSkipLesson = false; ?>
            <?php foreach ($course->LessonsGroups as $groupNum => $lessonGroup) : ++$groupNum; ?>
                <h1 class='theme-name'><?php echo "Шаг $groupNum: \"$lessonGroup->name\""; ?></h1>
                    <table class='lessons-table'>
                        <colgroup>
                         <col width="50%">
                         <col width="18%">
                         <col width="10%">
                         <col width="22%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class='name'>Название урока</th>
                                <th colspan="2" class='status'>Состояние</th>
                                <th class="export">Экспорт урока</th>
                            </tr>
                        </thead>
                        
                        <?php
                            $themeLessons = $lessonGroup->LessonsRaw;
                            if(!$isSkipLesson && $posLesson==1) {
                                $isSkipLesson=true;
                                unset($themeLessons[0]);
                            }
                        ?>
                        
                        <?php if ($themeLessons) : ?>
                            <?php foreach ($themeLessons as $keyLesson => $lesson) : ?>
                                <?php if($userAndLesson = UserAndLessons::existLesson($course->id, $lessonGroup->id, $lesson->id)) : ?>
                                    <tr>
                                        <td>
                                            <?php echo CHtml::link("Урок $posLesson : ".$lesson->name, array('lessons/pass', 'id' => $userAndLesson->id), array('class'=>'lesson-name')); ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($userAndLesson->passed) : ?>
                                                <p class="passed">Пройден</p>
                                            <?php else : ?>
                                                <div class="percent">
                                                    <?php echo $userAndLesson->LessonProgress; ?>
                                                    <span>%</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php $imageName = $userAndLesson->passed ? 'repeat' : 'play'; ?>
                                            <?php echo CHtml::link("<img src='/images/$imageName.png' width='37' height='36' />", array('lessons/pass', 'id' => $userAndLesson->id), array('class'=>'to-lesson', 'onclick'=>"reachGoal('AnyCourseLessonStartIndex')")); ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="export-button">
                                                <button type="button" class="dropdown-toggle" data-toggle="dropdown">Экспорт<span class="caret"></span></button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    <li><a href="<?php echo Yii::app()->createUrl('lessons/printLesson', array('id'=>$userAndLesson->id, 'with_right'=>0)); ?>" target="_blank">Печать</a></li>
                                                    <li><a href="<?php echo Yii::app()->createUrl('lessons/lessonToPdf', array('id'=>$userAndLesson->id, 'with_right'=>0)); ?>" target="_blank">PDF</a></li>
                                                </ul>
                                                <?php if(Yii::app()->user->checkAccess('editor')) : ?>
                                                    <div class='with-right-cont'>
                                                        <input id='with-right-<?php echo $posLesson; ?>' type='checkbox' class='with-right' name value='0' />
                                                        <label for='with-right-<?php echo $posLesson; ?>'>С ответами</label>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td><?php echo "Урок $posLesson : $lesson->name"; ?></td>
                                        <td style="text-align: center;">
                                            <p class="unaccess">Не пройден</p>
                                        </td> 
                                        <td></td> 
                                        <td></td> 
                                    </tr>
                                <?php endif; ++$posLesson; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td class="no-lessons" colspan="4">Нет уроков</td>
                            </tr>
                        <?php endif; ?>
                    </table>
            <?php endforeach; ?>
        <?php else : ?>
            Курс пуст
        <?php endif; ?>
    </div>
</div>