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
            <div class="row">
                <div class="col-lg-2 col-md-2">
                    <?php echo CHtml::label('Введите ваш email', 'email'); ?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <?php echo CHtml::textField('email', $email, array('size'=>60,'maxlength'=>100, 'class'=>'form-control', "placeholder"=>'Введите ваш email')); ?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="errorMessage"><?php echo $error; ?></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <?php echo CHtml::submitButton('Восстановить', array('class'=>'btn btn-primary')); ?>
                </div>
            </div>
        <?php $this->endWidget(); ?>
    </div>
</div>