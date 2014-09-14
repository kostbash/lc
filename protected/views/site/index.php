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

<script type="text/javascript">
    $(function(){
        $('#reg-as-student').click(function(){
            $('#user-role-student').attr('checked', 'checked');
        });
        
        $('#reg-as-teacher').click(function(){
            $('#user-role-teacher').attr('checked', 'checked');
        });
        
        $('#reg-as-parent').click(function(){
            $('#user-role-parent').attr('checked', 'checked');
        });
        
        $('#courses-tabs li').click(function(){
            current = $(this);
            triangle = current.closest('.nav-tabs').find('li .triangle');
            current.closest('.nav-tabs').find('li .course-shadow').show();
            current.find('.course-shadow').hide();
            current.append(triangle);
        });
    });
</script>
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
    <div id="courses-tabs">
        <ul class="nav nav-tabs">
            <li class="active">
                <div href="#panel-1" data-toggle="tab" class="tab-header">
                    <div class='course-shadow' style='display: none;'></div>
                    <img class="course-icon" src="/images/math-icon.png" />
                    <div class="name">
                        <h3>Математика</h3>
                    </div>
                </div>
                <div class="triangle">
                    <img src="/images/triangle-active.png" width="25" height="11" />
                </div>
            </li>
            <li>
                <div href="#panel-2" data-toggle="tab" class="tab-header">
                    <div class='course-shadow'></div>
                    <img class="course-icon" src="/images/english-icon.png" />
                    <div class="name">
                        <h3>Английский язык</h3>
                    </div>
                </div>
            </li>
            <li>
                <div href="#panel-3" data-toggle="tab" class="tab-header">
                    <div class='course-shadow'></div>
                    <img class="course-icon" src="/images/russian-icon.png" />
                    <div class="name">
                        <h3>Русский язык</h3>
                    </div>
                </div>
            </li>
            <li class="fourth">
                <div href="#panel-4" data-toggle="tab" class="tab-header">
                    <div class='course-shadow'></div>
                    <img class="course-icon" src="/images/history-icon.png" />
                    <div class="name">
                        <h3>История</h3>
                    </div>
                </div>
            </li>
            <li class="dropdown open" id="more-courses">
                <a href="#" id="tabDrop1" class="dropdown-toggle" data-toggle="dropdown"><img src="/images/vertical-dotes.png" width="6" height="24" /></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="tabDrop1">
                    <li class=""><a href="#dropdown1" tabindex="-1" data-toggle="tab">@fat</a></li>
                    <li class=""><a href="#dropdown2" tabindex="-1" data-toggle="tab">@mdo</a></li>
                </ul>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="panel-1">
                <div class="courses clearfix">
                    <div class="course">
                        <div class="head">
                            <div class="number">1</div>
                            <div class="name">Сложение в пределах 100</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">1</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">5</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                    <div class="course">
                        <div class="head">
                            <div class="number">1</div>
                            <div class="name">Сложение в пределах 100</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">1</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">5</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                    <div class="course">
                        <div class="head">
                            <div class="number">156</div>
                            <div class="name">Сложение в пределах 100</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">12</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">5</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                    <div class="course">
                        <div class="head">
                            <div class="number">55</div>
                            <div class="name">Сложение в пределах 100</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">10</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">99</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="panel-2">
                <div class="courses clearfix">
                    <div class="course">
                        <div class="head">
                            <div class="number">1</div>
                            <div class="name">Сложение в пределах 100000000000000</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">005</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">5</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                    <div class="course">
                        <div class="head">
                            <div class="number">1</div>
                            <div class="name">Сложение в пределах 0</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">1</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">5</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                    <div class="course">
                        <div class="head">
                            <div class="number">22</div>
                            <div class="name">Сложение в пределах 100</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">12</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">5</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                    <div class="course">
                        <div class="head">
                            <div class="number">55</div>
                            <div class="name">Сложение в пределах 100</div>
                        </div>
                        <div class="content clearfix">
                            <div class="class">
                                <h4>Класс:</h4>
                                <div class="value">10</div>
                            </div>
                            <div class="difficulty">
                                <h4>Сложность:</h4>
                                <div class="value">99</div>
                            </div>
                            <div class="count-lessons">
                                <h4>Уроков:</h4>
                                <div class="value">30</div>
                            </div>
                        </div>
                        <div class="foot">
                            <a class='to-course' href='#'>к курсу</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="panel-3">

            </div>
            <div class="tab-pane" id="panel-4">

            </div>
        </div>
    </div>
</div>