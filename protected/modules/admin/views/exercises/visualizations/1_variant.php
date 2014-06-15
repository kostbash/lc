<div class="row variant" data-index='<?php echo $index; ?>'>
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-5 col-md-5">
        <?php echo CHtml::textField("Exercises[answers][$index][answer]", $answer->answer, array('class'=>'form-control', 'placeholder'=>'Введите правильный ответ')); ?>
        <?php echo CHtml::hiddenField("Exercises[answers][$index][is_right]", 1); ?>
        <div class="errorMessage"></div>
    </div>
    <div class="col-lg-2 col-md-2">
        <?php echo CHtml::checkBox("Exercises[answers][$index][reg_exp]", $answer->reg_exp, array('style'=>'width: 16%; vertical-align: top')); ?>
        <?php echo CHtml::label("Регулярное выражение", "Exercises_answers_{$index}_reg_exp", array('style'=>'font-size: 14px; line-height: 16px; width: 80%; cursor: pointer')); ?>
        <div class="errorMessage"></div>
    </div>
    <div class="col-lg-2 col-md-2" style='text-align: right'>
        <?php echo CHtml::link('Удалить', '#', array('class'=>'btn btn-danger delete-variant')); ?>
    </div>
</div>