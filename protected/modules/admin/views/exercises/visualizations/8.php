<div id="space-text">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <?php echo CHtml::textArea("Exercises[questions][0][text]", $model->Questions[0]->text, array('class'=>'form-control question-text', 'rows'=>'3', 'placeholder'=>'Введите текст')); ?>
            <div class="errorMessage"></div>
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::dropDownList('', '', $model->dataSpaces, array('class'=>'spaces form-control', 'size'=>2)); ?>
            <div class="errorMessage"></div>
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::dropDownList('', '', CHtml::listData($model->rightAnswersOrderSpace, 'id', 'answer'), array('id'=>false, 'class'=>'form-control answers', 'size'=>2)); ?>
            <div class="errorMessage"></div>
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::link('Удалить выделенное', '#', array('class'=>'btn btn-sm btn-danger delete-answer')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div style="font-style: italic; font-size: 13px">Примечание: чтобы создать пробел надо написать в тексте буквы sp и любое число. Например: sp1</div>
        </div>
        <div class="col-lg-offset-2 col-md-offset-2 col-lg-2 col-md-2">
                <?php echo CHtml::textField('', '', array('class'=>'form-control new-answer input-sm', 'id'=>false, 'placeholder'=>'Введите ответ')); ?>
        </div>
        <div class="col-lg-2 col-md-2">
                <?php echo CHtml::link('Добавить', '#', array('class'=>'btn btn-success btn-sm add-answer')); ?>
        </div>
    </div>
    <div id="hidden-options">
        <?php foreach($model->rightAnswersOrderSpace as $answer) : ?>
            <input data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][answer]" value="<?php echo $answer->answer; ?>">
        <?php endforeach; ?>
    </div>
</div>
