    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Восстановление пароля</div>
                    <div class="foot">Введите свой адрес электронной почты. На этот адрес будет отправлено письмо со ссылкой на восстановление пароля.</div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="content">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-form',
                'enableAjaxValidation'=>false,
        )); ?>
            <div class="row row-attr">
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::label('Введите Ваш псевдоним', 'username'); ?>
                </div>
                <div class="col-lg-6 col-md-6">
                    <?php echo CHtml::textField('username', $username, array('size'=>60,'maxlength'=>100, 'class'=>'form-control', "placeholder"=>'Введите ваш логин')); ?>
                    <div class="errorMessage"><?php echo $errorUsername; ?></div>
                </div>
            </div>
        
            <div class="row row-attr">
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::label('Введите Ваш ответ на контрольный вопрос', 'recovery_answer'); ?>
                </div>
                <div class="col-lg-6 col-md-6">
                    <?php echo CHtml::textField('recovery_answer', $recoveryAnswer, array('size'=>60,'maxlength'=>100, 'class'=>'form-control', "placeholder"=>'Введите ваш ответ на вопрос')); ?>
                    <div class="errorMessage"><?php echo $errorRecoveryAnswer; ?></div>
                </div>
            </div>

            <div class="row row-attr">
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::submitButton('Восстановить', array('class'=>'btn btn-primary')); ?>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="errorMessage"><?php echo $error; ?></div>
                </div>
            </div>
        <?php $this->endWidget(); ?>
    </div>
</div>