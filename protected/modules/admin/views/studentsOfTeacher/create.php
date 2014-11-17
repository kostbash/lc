<div class="page-header clearfix">
    <h2>Добавление ученика</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Создать', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>'$("#students-of-teacher-form").submit();')); ?>
</div>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'students-of-teacher-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row form-field">
            <div class="col-lg-3 col-md-3">
                <?php echo $form->labelEx($user,'username'); ?>
            </div>
            <div class="col-lg-4 col-md-4">
		<?php echo $form->textField($user, 'username', array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите логин ученика')); ?>
		<?php echo $form->error($user,'username'); ?>
            </div>
	</div>
	<div class="row form-field">
            <div class="col-lg-3 col-md-3">
		<?php echo $form->labelEx($model,'student_name'); ?>
            </div>
            <div class="col-lg-4 col-md-4">
		<?php echo $form->textField($model,'student_name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите имя ученика')); ?>
		<?php echo $form->error($model,'student_name'); ?>
            </div>
	</div>
	<div class="row form-field">
            <div class="col-lg-3 col-md-3">
		<?php echo $form->labelEx($model,'student_surname'); ?>
            </div>
            <div class="col-lg-4 col-md-4">
                <?php echo $form->textField($model,'student_surname',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите фамилию ученика')); ?>
                <?php echo $form->error($model,'student_surname'); ?>
            </div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->