    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Ваш пароль: <span style="font-size: 24px; font-weight: bold; text-transform: none;"><?php echo $user->temporary_password; ?></span></div>
                    <div class="foot">
                        Запишите этот пароль. Он будет нужен для того чтобы обеспечить безопасноть вашего аккаунта. Пароль больше не будет показан, так как хранится в зашифорванном виде.
                        Чтобы не вводить пароль при каждом входе в систему проверьте что установлен флажок "Узнавать меня". В случае, если Вы забудете пароль, то его можно восстановить правильно ответив на контрольный вопрос.
                        <p style="margin-top: 10px"><b>Узнать меня</b> <?php echo CHtml::checkBox($name, $user->rememberMe, array('style'=>'vertical-align: middle; margin-top: -1px; margin-left: 3px;')); ?></p>
                        <p><b>Ваш контрольный вопрос:</b> <?php echo $user->niceRecoveryQuestion; ?></p>
                        <p><b>Ответ на контрольный вопрос:</b> <?php echo $user->recovery_answer ?></p>
                        
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