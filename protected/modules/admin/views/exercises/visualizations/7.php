<div class="row" id="sentences">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-8 col-md-8 answer">
        <?php echo CHtml::textArea("Exercises[answers][0][answer]", $model->Answers[0]->answer, array('class'=>'form-control', 'placeholder'=>'Введите предложение')); ?>
        <?php echo CHtml::hiddenField("Exercises[answers][0][is_right]", 1); ?>
        <div class="errorMessage"></div>
    </div>
</div>
