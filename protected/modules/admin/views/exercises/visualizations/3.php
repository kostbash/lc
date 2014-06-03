<div class="row">
    <div class="col-lg-4 col-md-4">
        <?php echo CHtml::label($model->getAttributeLabel('correct_answers'), ''); ?>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <?php echo CHtml::textField('Exercises[correct_answers]', $model->correct_answers, array('class'=>'form-control', 'placeholder'=>'Введите правильный ответ')); ?>
    </div>
</div>
