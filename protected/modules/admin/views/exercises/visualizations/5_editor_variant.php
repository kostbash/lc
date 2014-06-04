<div class="row editor-variant-cont" data-index='<?php echo $index; ?>'>
    <div class="col-lg-3 col-md-3">
        <input type='radio' name='Exercises[correct_answers]' value='<?php echo $index; ?>' />
        <?php echo CHtml::label("Варианты $index", ''); ?>
    </div>
    <div class="col-lg-7 col-md-7">
        <?php echo CHtml::hiddenField("Exercises[answers][$index]", ''); ?>
        <div class='for-editor-field' title='Нажмите, чтобы открыть редактор'>
            Введите текст
        </div>
        <div class="errorMessage"></div>
    </div>
    <div class="col-lg-2 col-md-2" style='text-align: right'>
        <?php echo CHtml::link('Удалить', '#', array('class'=>'btn btn-danger delete-editor-variant')); ?>
    </div>
</div>
