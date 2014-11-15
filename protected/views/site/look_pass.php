    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Ваш пароль: <span style="font-size: 24px; font-weight: bold; text-transform: none;"><?php echo $user->temporary_password; ?></span></div>
                    <div class="foot">
                        Ваш пароль отправлен на указанный e-mail, но также вы можете записать его на бумагу.
                        <div style="text-align: center; margin-top: 15px;">
                            <?php echo CHtml::link('Начать работу с сайтом', $link, array('class'=>'next-button', 'onclick'=>"reachGoal(RegisterStudentFinish')")); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container"></div>