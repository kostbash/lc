    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Профиль пользователя "<?php echo $user->username; ?>"</div>
                    <div class="foot">
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="content">
        <?php $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>