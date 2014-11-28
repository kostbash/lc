<?php 
    $this->pageTitle=Yii::app()->name." - Обучающие курсы для школьников";
    $messages = SourceMessages::MessagesByCategories(array('main-page-unauth', 'courses'));
?>
    <?php $this->renderPartial('//site/reg_login', array('user'=>$user, 'login'=>$loginForm)); ?>
    <?php if($regLoginType && $tab) : ?>
        <script>
            type = '<?php echo $regLoginType; ?>';
            tab = '<?php echo $tab; ?>';
            if(type==='login')
            {
                loginButton(tab);
            }
            else if(type==='registration')
            {
                registrationButton(tab, false);
            }
            else if(type=='begin-learning')
            {
                beginLearningButton(tab, false);
            }
            
            $('#regLogin').removeClass('fade').modal('show').addClass('fade');
        </script>
    <?php endif; ?>
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
    <h1 class="choose-course"><?php echo Yii::t('main-page-unauth', $messages[10]->message); ?></h1>
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
                                            <a class='to-course' href='".Yii::app()->createUrl('courses/view', array('id'=>$subjectCourse->id))."'>
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
                                                    <div class='link-name'>К курсу <i class='glyphicon glyphicon-arrow-right' style='top:2px;'></i></div>
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