<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo CHtml::label($model->getAttributeLabel('correct_answers'), ''); ?>
    </div>
    
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('Exercises[correct_answers]', $model->correct_answers, array('class'=>'form-control', 'placeholder'=>'Введите правильный ответ')); ?>
        <div class="errorMessage"></div>
    </div>
</div>
