    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Результат теста - <?php echo $result['mark']; ?></div>
                    <div class="foot">
                        <p>Правильных ответов <b><?php echo $rightAnswers; ?> из <?php echo $numberAll; ?>.</b></p>
<!--                        <div style="width: 489px; padding: 40px 22px;">
                            <?php echo MyWidgets::ProgressBarWithLimiter($numberAll, $rightAnswers); ?>
                        </div>-->
                        <p><?php echo $result['recommendation']; ?></p>
                        <p><?php echo CHtml::link("Начать обучение", array('site/index', 'showreg'=>true), array('class'=>'btn btn-success')); ?></p>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
</div>