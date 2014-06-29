<div class="row variant" data-index='<?php echo $index; ?>'>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::hiddenField("Exercises[Comparisons][$index][answer_one][answer]", $comparison->AnswerOne->answer, array('id'=>false, 'class'=>'hidden-answer')); ?>
        <div class='for-editor-field' title='Нажмите, чтобы открыть редактор'>
            <?php echo $comparison->AnswerOne->answer ? $comparison->AnswerOne->answer : 'Введите текст'; ?>
        </div>
        <div class="errorMessage"></div>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::hiddenField("Exercises[Comparisons][$index][answer_two][answer]", $comparison->AnswerTwo->answer, array('id'=>false, 'class'=>'hidden-answer')); ?>
        <div class='for-editor-field' title='Нажмите, чтобы открыть редактор'>
            <?php echo $comparison->AnswerTwo->answer ? $comparison->AnswerTwo->answer : 'Введите текст'; ?>
        </div>
        <div class="errorMessage"></div>
    </div>
    <div class="col-lg-2 col-md-2" style='text-align: right'>
        <?php echo CHtml::link('Удалить', '#', array('class'=>'btn btn-danger delete-variant')); ?>
    </div>
</div>
