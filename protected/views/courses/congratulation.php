    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Завершен курс <?php echo CHtml::link("<$course->name>", array('courses/index', 'id'=>$course->id)); ?></div>
                    <div class="foot">
                        Отличная работа!
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div class="info-page">
        <div id="congratulation">
            <?php echo $course->congratulation; ?>
        </div>
        <div style="text-align: center; margin-top: 24px;">
            <?php echo CHtml::link("Закрыть", array('courses/list'), array('class'=>'next-button')); ?>
        </div>
    </div>
</div>