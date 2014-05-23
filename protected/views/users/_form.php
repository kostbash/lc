<div class="form-profile">
<h4>Сменить пароль: </h4>
    
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
            <div class="col-lg-2 col-md-2">
		<?php echo CHtml::label('Новый пароль: ','Users_password'); ?>
            </div>
            <div class="col-lg-3 col-md-3">
		<?php echo CHtml::passwordField('Users[password]', '', array('class'=>'form-control', 'placeholder'=>'Введите новый пароль')); ?>
            </div>
            <div class="col-lg-3 col-md-3">
		<?php echo $form->error($model,'password'); ?>
            </div>
	</div>
	<div class="row">
            <div class="col-lg-2 col-md-2">
		<?php echo CHtml::label('Подтвердите пароль: ','Users_password'); ?>
            </div>
            <div class="col-lg-3 col-md-3">
		<?php echo $form->passwordField($model,'checkPassword', array('class'=>'form-control', 'placeholder'=>'Введите еще раз пароль', 'value'=>false)); ?>
            </div>
            <div class="col-lg-3 col-md-3">
		<?php echo $form->error($model,'checkPassword'); ?>
            </div>
	</div>
	<div class="row">
            <div class="col-lg-4 col-md-4 col-lg-offset-2 col-md-offset-2">
                <?php echo $form->checkBox($model,'sendOnMail', array('style'=>'vertical-align:middle; margin: 0 0 2px')); ?>
		<?php echo $form->labelEx($model,'sendOnMail'); ?>
            </div>
	</div>

	<div class="row buttons">
            <div class="col-lg-4 col-md-4">
		<?php echo CHtml::submitButton('Применить', array('class'=>'btn btn-primary btn-sm')); ?>
            </div>
	</div>

<?php $this->endWidget(); ?>
    
<h4>Сменить email: </h4>
<?php $form2=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form2',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
            <div class="col-lg-2 col-md-2">
		<?php echo CHtml::label('Новый email','Users[email]'); ?>
            </div>
            <div class="col-lg-3 col-md-3">
		<?php echo CHtml::textField('Users[email]', '', array('size'=>60,'maxlength'=>100, 'class'=>'form-control', "placeholder"=>'Введите новый email')); ?>
            </div>
            <div class="col-lg-3 col-md-3">
		<?php echo $form2->error($model,'email'); ?>
            </div>
	</div>

	<div class="row">
            <div class="col-lg-4 col-md-4">
		<?php echo CHtml::submitButton('Применить', array('class'=>'btn btn-primary btn-sm')); ?>
            </div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->