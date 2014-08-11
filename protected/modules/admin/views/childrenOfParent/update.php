<div class="page-header clearfix">
    <h2>Настройки ученика "<?php echo $cloneModel->child_name." ".$cloneModel->child_surname; ?>"</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>'$("#children-of-parent-form").submit();')); ?>
</div>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'children-of-parent-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row form-field">
            <div class="col-lg-3 col-md-3">
		<?php echo $form->labelEx($model,'child_name'); ?>
            </div>
            <div class="col-lg-4 col-md-4">
		<?php echo $form->textField($model,'child_name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите имя ребенка')); ?>
		<?php echo $form->error($model,'child_name'); ?>
            </div>
	</div>
	<div class="row form-field">
            <div class="col-lg-3 col-md-3">
		<?php echo $form->labelEx($model,'child_surname'); ?>
            </div>
            <div class="col-lg-4 col-md-4">
                <?php echo $form->textField($model,'child_surname',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите фамилию ребенка')); ?>
                <?php echo $form->error($model,'child_surname'); ?>
            </div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->