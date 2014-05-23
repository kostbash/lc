<div id="check">
    <div class="row">
        <div class="head col-lg-8 col-md-8">
            <h1>Результаты теста</h1>
        </div>
    </div>
    <div id="result">
        <div><?php echo $result['mark']; ?></div>
        <div>Правильных ответов <b><?php echo $rightAnswers; ?> из <?php echo $numberAll; ?>.</b></div>
    </div>
    <div class="row" style="margin-bottom: 40px">
        <div id="recommend" class="col-lg-3 col-md-3">
            <?php echo $result['recommendation']; ?>
        </div>
        <div class="col-lg-2 col-md-2" style="margin-top: -5px">
            <?php echo CHtml::link("Начать обучение", array('site/index', 'showreg'=>true), array('class'=>'btn btn-success')); ?>
        </div>
    </div>
</div>
