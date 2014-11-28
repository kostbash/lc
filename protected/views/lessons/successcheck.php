<?php
    $messages = SourceMessages::MessagesByCategories(array('check-test-result'));
?>
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
                        <p><?php echo $result['recommendation']; ?></p>
                        
                        <?php
                            if(Yii::app()->user->isGuest)
                            {
                                $link = '#';
                                $linkAttrs = array('class'=>'next-button begin-learning', 'data-toggle'=>"modal", 'data-target'=>"#regLogin", 'onclick'=>"reachGoal('AnyCourseStartGuest')");
                            }
                            else
                            {
                                $link = array('courses/index', 'id'=>$course->id);
                                $linkAttrs = array('class'=>'next-button begin-learning');
                            }
                        ?>
                        
                        <?php echo CHtml::link(Yii::t('check-test-result', $messages[22]->message), $link, $linkAttrs); ?>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
</div>