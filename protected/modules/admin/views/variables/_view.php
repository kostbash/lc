<?php
/* @var $this VariablesController */
/* @var $data Variables */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_course')); ?>:</b>
	<?php echo CHtml::encode($data->id_course); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_value')); ?>:</b>
	<?php echo CHtml::encode($data->default_value); ?>
	<br />


</div>