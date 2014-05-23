<?php
/* @var $this ExercisesController */
/* @var $data Exercises */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('question')); ?>:</b>
	<?php echo CHtml::encode($data->question); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('correct_answer')); ?>:</b>
	<?php echo CHtml::encode($data->correct_answer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('difficulty')); ?>:</b>
	<?php echo CHtml::encode($data->difficulty); ?>
	<br />


</div>