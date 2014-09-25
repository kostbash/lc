    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Восстановление пароля</div>
                    <div class="foot">
                        Восстановление доступа к аккаунту <b><?php echo $user->email; ?></b>.
                        Введите новый пароль
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="content">
        <div id="recovery-password">
            <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'users-form',
                    'enableAjaxValidation'=>false,
            )); ?>
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <?php echo CHtml::label('Новый пароль: ','Users_password'); ?>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <?php echo $form->passwordField($user,'password', array('class'=>'form-control', 'placeholder'=>'Введите новый пароль')); ?>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <?php echo $form->error($user,'password'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <?php echo CHtml::label('Подтвердите пароль: ','Users_password'); ?>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <?php echo $form->passwordField($user,'checkPassword', array('class'=>'form-control', 'placeholder'=>'Введите еще раз пароль', 'value'=>false)); ?>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <?php echo $form->error($user,'checkPassword'); ?>
                    </div>
                </div>

                <div class="row buttons">
                    <div class="col-lg-4 col-md-4">
                        <?php echo CHtml::submitButton('Восстановить', array('class'=>'btn btn-primary')); ?>
                    </div>
                </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>