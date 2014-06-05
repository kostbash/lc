<div class="row editor-variant-cont" data-index='<?php echo $index; ?>'>
    <div class="col-lg-3 col-md-3">
        <?php 
            if(isset($model) && isset($answer))
                $checked = $model->correct_answers == $answer->id ? ' checked="checked" ' : '';
        ?>
        <input id="<?php echo "radio-variant-$index"; ?>" type='radio' name='Exercises[correct_answers]' <?php echo $checked; ?> value='<?php echo $index; ?>' />
        <?php echo CHtml::label("Варианты $index", "radio-variant-$index"); ?>
    </div>
    <div class="col-lg-7 col-md-7">
        <?php echo CHtml::hiddenField("Exercises[answers][$index]", $answer->answer, array('id'=>false, 'class'=>'hidden-answer')); ?>
        <div class='for-editor-field' title='Нажмите, чтобы открыть редактор'>
            <?php echo $answer->answer ? $answer->answer : 'Введите текст'; ?>
        </div>
        <div class="errorMessage"></div>
    </div>
    <div class="col-lg-2 col-md-2" style='text-align: right'>
        <?php echo CHtml::link('Удалить', '#', array('class'=>'btn btn-danger delete-editor-variant')); ?>
    </div>
</div>
