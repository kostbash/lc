<?php 
    $messages = SourceMessages::MessagesByCategories(array('for-parents'));
?>

    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head"><?php echo Yii::t('for-parents', $messages[57]->message); ?></div>
                    <div class="foot">
                        
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div class="info-page">
        <?php echo Yii::t('for-parents', $messages[58]->message); ?>
    </div>
</div>