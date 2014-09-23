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
    <div id="course-view-page">
        <h2 class="main-title"><?php echo $course->name; ?></h2>
        <div id="top-buttons">
            <?php echo CHtml::link('Начать обучение', '#', array('class'=>'next-button', 'data-toggle'=>"modal", 'data-target'=>"#regModel" )); ?>
            <?php echo CHtml::link('Проверить себя', array('lessons/check', 'course'=>$course->id), array('class'=>'send-result-button')); ?>
        </div>
        <div id="course-info" class="clearfix">
            <div class="get">
                <div class="content">
                    <div class="info">
                        <div class="name">ЧТО ПОЛУЧИТЕ:</div>
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
                        <div class="name">ЧТО НУЖНО:</div>
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
                        <div class="name">СТАТИСТИКА:</div>
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
        
        <h2 class="main-title">Список уроков:</h2>
        
        <table id="lessons-table">
            <thead>
                <tr>
                    <th class='number'>Номер</th>
                    <th class='name'>Название урока</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($themesLessons) > 1) : // убераем проверочный тест ?>
                <?php foreach ($themesLessons as $n => $lesson) : ?>
                    <?php if($n==0) { continue; } ?>
                    <tr>
                        <td class="number">
                            <?php echo $n; ?>
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
    </div>
</div>