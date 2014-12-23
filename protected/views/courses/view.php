<?php
    $this->pageTitle="$course->name. ".Yii::app()->name.".";
    $messages = SourceMessages::MessagesByCategories(array('main-page-unauth', 'course-unauth', 'course-pages'));
?>
    <div id="separate-header-part">
        <img src="/images/separate-adventages.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="advantages">
                <div class="advantage">
                    <img src="/images/books-main.png" width="169" height="169" />
                    <div class="explanation">
                        <?php echo Yii::t('main-page-unauth', $messages[5]->message); ?>
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/idea-main.png" width="169" height="169" />
                    <div class="explanation">
                        <?php echo Yii::t('main-page-unauth', $messages[6]->message); ?>
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/statistic-main.png" width="169" height="169" />
                    <div class="explanation">
                        <?php echo Yii::t('main-page-unauth', $messages[7]->message); ?>
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/person-main.png" width="169" height="169" />
                    <div class="explanation">
                        <?php echo Yii::t('main-page-unauth', $messages[8]->message); ?>
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/cloud-main.png" width="169" height="169" />
                    <div class="explanation">
                        <?php echo Yii::t('main-page-unauth', $messages[9]->message); ?>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="course-view-page">
        <h2 class="main-title">
            <?php echo CHtml::link('Курсы', array('site/index'), array('style'=>'color: #feffff;')); ?>
            <i class="glyphicon glyphicon-arrow-right" style="top:4px;"></i>
            <?php echo $course->name; ?>
            <div class="share">
                <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
                <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir" data-yashareTheme="counter"></div>
            </div>
        </h2>
        <div id="top-buttons">
            <?php echo CHtml::link(Yii::t('course-unauth', $messages[11]->message), '#', array('class'=>'next-button begin-learning', 'data-toggle'=>"modal", 'data-target'=>"#regLogin", 'onclick'=>Yii::app()->user->isGuest?"reachGoal('AnyCourseStartGuest')":'' )); ?>
            <?php echo CHtml::link(Yii::t('course-pages', $messages[12]->message), array('lessons/check', 'course'=>$course->id), array('class'=>'send-result-button')); ?>
        </div>
        <div id="course-info" class="clearfix">
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
        
        <h2 class="main-title"><?php echo Yii::t('course-unauth', $messages[16]->message); ?></h2>
        
        <?php if ($course->LessonsGroups) : $posLesson = 1; $isSkipLesson = false; ?>
            <?php foreach ($course->LessonsGroups as $groupNum => $lessonGroup) : ++$groupNum; ?>
                <h1 class='theme-name'><?php echo "Шаг $groupNum: \"$lessonGroup->name\""; ?></h1>
                <?php
                    $themeLessons = $lessonGroup->LessonsRaw;
                    if(!$isSkipLesson && $posLesson==1) {
                        $isSkipLesson=true;
                        unset($themeLessons[0]);
                    }
                ?>
                <table class="lessons-table">
                    <thead>
                        <tr>
                            <th class='number'>Номер</th>
                            <th class='name'>Название урока</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($themeLessons) : ?>
                        <?php foreach ($themeLessons as $lesson) : ?>
                            <tr>
                                <td class="number">
                                    <?php echo $posLesson++; ?>
                                </td>
                                <td class="name">
                                    <?php echo $lesson->name; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="2">Нет уроков</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else : ?>
            Курс пуст
        <?php endif; ?>
    </div>
</div>