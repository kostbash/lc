<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
)); ?>
<div class="modal fade" id="loginForm" tabindex="-1" role="dialog" aria-labelledby="loginFormLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="loginFormLabel">Войти</h4>
      </div>
      <div class="modal-body">
	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
            <div class="body-input">
		<?php echo $form->textField($model,'username', array('class'=>'form-control', 'placeholder'=>'Введите логин')); ?>
		<?php echo $form->error($model,'username'); ?>
            </div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
            <div class="body-input">
		<?php echo $form->passwordField($model,'password', array('class'=>'form-control', 'placeholder'=>'Введите пароль')); ?>
		<?php echo $form->error($model,'password'); ?>
            </div>
	</div>

	<div class="row rememberMe">
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">закрыть</button>
        <?php echo CHtml::submitButton('Войти', array("class"=>"btn btn-primary")); ?>
      </div>
    </div>
  </div>
</div>
<?php $this->endWidget(); ?>
