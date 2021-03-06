<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="widget">
<h1>Обратная связь</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
Задайте любой, интересующий вас вопрос или поделитесь с нами своими впечатлениями о курсе и системе.
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row form-group">
            <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'name'); ?></div>
            <div class="col-lg-7 col-md-7"><?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?></div>
		
		
	</div>

	<div class="row form-group">
            <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'email'); ?></div>
            <div class="col-lg-7 col-md-7"><?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?></div>
		
		
	</div>

	<div class="row form-group">
            <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'subject'); ?></div>
            <div class="col-lg-7 col-md-7"><?php echo $form->textField($model,'subject',array('size'=>30,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'subject'); ?></div>
		
		
	</div>

	<div class="row form-group">
            <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'body'); ?></div>
            <div class="col-lg-7 col-md-7"><?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'body'); ?></div>
		
		
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row form-group">
            <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'verifyCode'); ?></div>
            <div class="col-lg-7 col-md-7">
                <div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode', array('class'=>'form-control')); ?>
		</div>
		<div class="hint">Пожалуйста, введите символы, показанные на рисунке.
		<br/>Регистр букв значения не имеет.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
            </div>
		
	</div>
	<?php endif; ?>

	<div class="row buttons">
            <div class="col-lg-2 col-md-2"><?php echo CHtml::submitButton('Отправить сообщение', array('class'=>'btn btn-primary')); ?></div>
		
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
</div>
    </div></div>