<?php
    $messages = SourceMessages::MessagesByCategories(array('courses','courses-page-auth'));
?>

    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-col-left" class="head-column">
                <div class="content">
                    <div class="course">
                        <?php if($lastActiveCourse) : ?>
                        <div class="head">
                            <div class="info">
                                <div class="status last-active"><?php echo Yii::t('courses-page-auth', $messages[37]->message); ?></div>
                                <div class="name"><?php echo $lastActiveCourse->name; ?></div>
                            </div>
                            <?php echo CHtml::link(Yii::t('courses-page-auth', $messages[36]->message), array('courses/index', 'id'=>$lastActiveCourse->id), array('class'=>'continue-course-button', 'onclick'=>"reachGoal('HomeLastLesson')")); ?>
                        </div>
                        <div class="content clearfix">
                            <div class="passed-lessons">
                                <h4><?php echo Yii::t('courses-page-auth', $messages[38]->message); ?></h4>
                                <div class="value"><?php echo $lastActiveCourse->countPassedLessons; ?> <span>из</span> <?php echo $lastActiveCourse->countLessons; ?></div>
                            </div>
                            <div class="average">
                                <h4><?php echo Yii::t('courses-page-auth', $messages[39]->message); ?></h4>
                                <div class="value"><?php echo $lastActiveCourse->averageByTests; ?> <span>%</span></div>
                            </div>
                        </div>
                        <div class="progress progress-striped active">
                            <?php $lastActiveCourseProgress = $lastActiveCourse->progress; ?>
                            <div class="progress-bar progress-bar-warning"  role="progressbar" aria-valuenow="<?php echo $lastActiveCourseProgress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $lastActiveCourseProgress; ?>%"></div>
                        </div>
                        <?php else : ?>
                            <div class="head">
                                <div class="info">
                                    <div class="name"><?php echo Yii::t('courses-page-auth', $messages[40]->message); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id="head-col-right" class="head-column">
                <div class="content">
                    <div class="text clearfix">
                        <div class="active-courses">
                            <div class="head"><?php echo Yii::t('courses-page-auth', $messages[41]->message); ?></div>
                            <ul>
                                <?php if($activeCourses) : ?>
                                    <?php foreach($activeCourses as $activeCourse) : ?>
                                        <li><?php echo CHtml::link($activeCourse->name, array('courses/index', 'id'=>$activeCourse->id)); ?></li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li><?php echo Yii::t('courses-page-auth', $messages[44]->message); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="passed-courses">
                            <div class="head"><?php echo Yii::t('courses-page-auth', $messages[42]->message); ?></div>
                            <ul>
                                <?php if($passedCourses) : ?>
                                    <?php foreach($passedCourses as $passedCourse) : ?>
                                        <li><?php echo CHtml::link($passedCourse->name, array('courses/index', 'id'=>$passedCourse->id)); ?></li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li><?php echo Yii::t('courses-page-auth', $messages[43]->message); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="courses-page">
    <?php
        if($newParent)
        {
            $this->renderPartial('//deals/_confirm_modal', array('newParent'=>$newParent));
        }
    ?>    
        
    <?php 
        if($subjects)
        {
            $mainNavTabs = '';
            $underNavTabs = '';
            $tabs = '';
            $zIndex = 4;
            foreach($subjects as $i => $subject)
            {
                if($i>=0 AND $i <=3)
                {
                    if($i==0)
                        $classNav = 'active';
                    elseif($i==3)
                        $classNav = 'fourth';
                    else
                        $classNav = '';
                    
                    $imageSize = getimagesize("images/tabCourseIcons/$subject->id.png");
                    if($imageSize)
                    {
                        $width = round($imageSize[0]/2, 0, PHP_ROUND_HALF_DOWN);
                        $image = "<div class='course-icon' style='width:{$width}px; height:$imageSize[1]px; background: url(/images/tabCourseIcons/$subject->id.png) right bottom no-repeat;'></div>";
                    }
                    else
                    {
                        $image = '';
                    }
                    
                    $mainNavTabs .= "<li class='$classNav' style='z-index: $zIndex'>
                                        <div href='#panel-$i' data-toggle='tab' class='tab-header'>
                                            $image
                                            <div class='name'>
                                                <h3>$subject->name</h3>
                                            </div>
                                        </div>
                                    </li>";
                    $zIndex--;
                }
                else
                {
                    $underNavTabs .= "<li><a href='#panel-$i' data-toggle='tab'>$subject->name</a></li>";
                }
                
                    if($i==0)
                        $classTab = 'active';
                    else
                        $classTab = '';
                
                $tabs .= "<div class='tab-pane $classTab' id='panel-$i'>";
                    $subjectCourses = $subject->Courses;
                    if($subjectCourses)
                    {
                        $tabs .="<div class='courses clearfix'>";
                            foreach($subjectCourses as $n => $subjectCourse)
                            {
                                $number = $n+1;
                                $tabs .="<div class='course'>
                                            <a class='to-course' href='".Yii::app()->createUrl('courses/index', array('id'=>$subjectCourse->id))."'>
                                                <div class='head'>
                                                    <div class='number'>$number</div>
                                                    <div class='name'>$subjectCourse->name</div>
                                                </div>
                                                <div class='content clearfix'>
                                                    <div class='class'>
                                                        <h4>Класс:</h4>
                                                        <div class='value'>$subjectCourse->className</div>
                                                    </div>
                                                    <div class='difficulty'>
                                                        <h4>Сложность:</h4>
                                                        <div class='value'>$subjectCourse->difficulty</div>
                                                    </div>
                                                    <div class='count-lessons'>
                                                        <h4>Уроков:</h4>
                                                        <div class='value'>$subjectCourse->countLessons</div>
                                                    </div>
                                                </div>
                                                <div class='foot'>
                                                    <div class='status'>$subjectCourse->userStatus</div>
                                                    <div class='link-name'>узнать больше</div>
                                                </div>
                                            </a>
                                        </div>";
                            }
                        $tabs .= "</div>";
                    }
                    else
                    {
                        $tabs .="<div class='no-courses'>";
                            $tabs .= Yii::t('courses', $messages[35]->message);
                        $tabs .= "</div>";
                    }
                $tabs .= "</div>";
            }
        }
    ?>
    <div id="courses-tabs">
        <ul class="nav nav-tabs">
            <?php echo $mainNavTabs;  ?>
            <li class="dropdown" id="more-courses">
                <a href="#" id="tabDrop1" class="dropdown-toggle" data-toggle="dropdown"><img src="/images/vertical-dotes.png" width="6" height="24" /></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="tabDrop1">
                    <?php echo $underNavTabs; ?>
                </ul>
            </li>
        </ul>

        <div class="tab-content">
            <?php echo $tabs; ?>
        </div>
    </div>
    </div>
</div>