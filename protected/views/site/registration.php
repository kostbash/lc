<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'reg-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
)); ?>
<div class="modal fade" id="regModel" tabindex="-1" role="dialog" aria-labelledby="regModelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="regModelLabel">Регистрация</h4>
      </div>
      <div class="modal-body clearfix">
	<div class="row">
            <div style="margin-bottom: 10px; text-align: left;">
                <h4 style="margin-top: 0;">Укажите адрес Вашей электронной почты</h4>
                <div>На этот адрес будет отослано письмо, содержащее пароль доступа к вашей учетной записи в Курсис.</div>
            </div>
            <?php echo $form->labelEx($model,'email'); ?>
            <div class="body-input">
		<?php echo $form->textField($model,'email', array('class'=>'form-control', 'placeholder'=>'Введите email')); ?>
		<?php echo $form->error($model,'email'); ?>
            </div>
	</div>
        <div class="row" id="reg-roles">
            <div class="role">
                <input id="user-role-student" type="radio" name="Users[role]" value="2" />
                <label for="user-role-student">Я ученик</label>
            </div>
            <div class="role">
                <input id="user-role-teacher" type="radio" name="Users[role]" value="3" />
                <label for="user-role-teacher">Я педагог</label>
            </div>
            <div class="role">
                <input id="user-role-parent" type="radio" name="Users[role]" value="4" />
                <label for="user-role-parent">Я родитель</label>
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
        <?php echo CHtml::submitButton('Перейти к курсу', array("class"=>"btn btn-primary")); ?>
      </div>
    </div>
  </div>
</div>
<?php $this->endWidget(); ?>