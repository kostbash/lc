<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo CHtml::label('Варианты ответов', ''); ?>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::dropDownList('Exercises[correct_answers]', $model->correct_answers, CHtml::listData($model->Answers, 'id', 'answer'), array('class'=>'form-control', 'placeholder'=>'Введите правильный ответ', 'size'=>2)); ?>
        <div class="errorMessage"></div>
        <div id="hidden-options"></div>
    </div>
    <div class="col-lg-2 col-md-2">
        <?php echo CHtml::link('Удалить', '#', array('class'=>'btn btn-danger delete-option')); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-3 col-md-3">
        <?php echo CHtml::textField('option-name', '', array('class'=>'form-control', 'id'=>'option-name', 'placeholder'=>'Введите название нового варианта')); ?>
    </div>
    <div class="col-lg-2 col-md-2" style='text-align: right;'>
        <?php echo CHtml::link('Добавить', '#', array('class'=>'btn btn-success add-option')); ?>
    </div>
</div>
