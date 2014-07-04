<?php
/* @var $this ExercisesController */
/* @var $model Exercises */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exercises-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'question'); ?>
		<?php echo $form->textArea($model,'question',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'question'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'correct_answer'); ?>
		<?php echo $form->textArea($model,'correct_answer',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'correct_answer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'difficulty'); ?>
		<?php echo $form->textField($model,'difficulty'); ?>
		<?php echo $form->error($model,'difficulty'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->