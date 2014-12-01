<div id="login-page">
    <div class="panel">
        <div class="panel-heading">
            <h3>Авторизация</h3>
        </div>
        <div class="panel-body">
            <div class="form">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
                ?>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'username'); ?>
                    <?php echo $form->textField($model, 'username', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'username'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'password'); ?>
                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'password'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->checkBox($model, 'rememberMe'); ?>
                    <?php echo $form->label($model, 'rememberMe'); ?>
                <?php echo $form->error($model, 'rememberMe'); ?>
                </div>
                <?php echo CHtml::submitButton('Вход', array('class' => 'btn btn-default login')); ?>
                <?php $this->endWidget(); ?>
            </div><!-- form -->
        </div>
    </div>
</div>
