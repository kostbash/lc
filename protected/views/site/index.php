<?php $this->pageTitle=Yii::app()->name; ?>
<?php if($showRegModal) : ?>
    <script type="text/javascript">
        $(function() {
            $('#regModel').removeClass('fade').modal('show');
        });
    </script>
<?php endif; ?>

<?php if($showLoginModal) : ?>
    <script type="text/javascript">
        $(function() {
            $('#loginForm').removeClass('fade').modal('show');
        });
    </script>
<?php endif; ?>
    <?php $this->renderPartial('login', array('model'=>$loginForm)); ?>
    <?php $this->renderPartial('registration', array('model'=>$user)); ?>
    <div id="separate-header-part">
        <img src="/images/separate-adventages.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="advantages">
                <div class="advantage">
                    <img src="/images/books-main.png" width="169" height="169" />
                    <div class="explanation">
                        КУРСЫ ЯДРА ШКОЛЬНОЙ ПРОГРАММЫ
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/idea-main.png" width="169" height="169" />
                    <div class="explanation">
                        САМОСТОЯТЕЛЬНАЯ ПРАКТИКА ДО ПОЛНОГО УСВОЕНИЯ
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/statistic-main.png" width="169" height="169" />
                    <div class="explanation">
                        СЛОЖНОСТЬ УПРАЖНЕНИЙ И ТЕСТОВ РАСТЕТ ПОСТЕПЕННО
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/person-main.png" width="169" height="169" />
                    <div class="explanation">
                        РОДИТЕЛЬСКИЙ КОНТРОЛЬ ЗА УСПЕХАМИ
                    </div>
                </div>
                <div class="advantage">
                    <img src="/images/cloud-main.png" width="169" height="169" />
                    <div class="explanation">
                        БЕСПЛАТНО!
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->
<div id="container">
    <h1 class="choose-course">Выберите предмет<br />и курс</h1>
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
                                                <a class='to-course' href='".Yii::app()->createUrl('courses/view', array('id'=>$subjectCourse->id))."'>к курсу</a>
                                            </div>
                                        </div>";
                            }
                        $tabs .= "</div>";
                    }
                    else
                    {
                        $tabs .= '<p style="margin-top:20px; margin-left: 10px">Курсов пока нет, они в разработке. В ближайшее время появятся :)</p>';
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