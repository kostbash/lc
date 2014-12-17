<?php
/* @var $this MailRulesController */
/* @var $data MailRules */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('use_number')); ?>:</b>
	<?php echo CHtml::encode($data->use_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('interval')); ?>:</b>
	<?php echo CHtml::encode($data->interval); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('passed_reg_days')); ?>:</b>
	<?php echo CHtml::encode($data->passed_reg_days); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unactivity_days')); ?>:</b>
	<?php echo CHtml::encode($data->unactivity_days); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number_of_passed_lessons')); ?>:</b>
	<?php echo CHtml::encode($data->number_of_passed_lessons); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('passed_course')); ?>:</b>
	<?php echo CHtml::encode($data->passed_course); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number_of_passed_courses')); ?>:</b>
	<?php echo CHtml::encode($data->number_of_passed_courses); ?>
	<br />

	*/ ?>

</div>