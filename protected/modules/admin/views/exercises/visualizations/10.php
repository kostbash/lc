<script>
    $(function(){
        $('.question-text-field').live('change', function() {
            question = $(this);
            val = question.val();
            template = /sp(\d+)/ig;
            data = new Array();
            i = 0;
            $('#spaces .variant').removeClass('dont-remove');
            while ( (res = template.exec(val)) != null )
            {
              variable = $('#spaces .variant[data-number='+res[1]+']');
              if(variable.length)
                  variable.addClass('dont-remove');
              else {
                data[res[1]] = res[0];
              }
            }

            $('#spaces .variant:not(.dont-remove)').remove();

            if(data.length)
            {
                for(var space in data)
                {
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl('admin/exercises/gethtmlvariant'); ?>',
                        data: { index: space, id_visual: $('#visualization').data('visual') },
                        type: 'POST',
                        dataType: 'json',
                        success: function(result) {
                            if(result.success)
                                $('#spaces').append(result.html);
                        }
                    });
                }
            }
            return false;
        });
    });
</script>
<div id="exact-answers-with-space">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <?php echo CHtml::textArea("Exercises[questions][0][text]", $model->Questions[0]->text, array('class'=>'form-control question-text-field', 'rows'=>'3', 'placeholder'=>'Введите текст')); ?>
            <div class="errorMessage"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div style="font-style: italic; font-size: 13px">Примечание: чтобы создать пробел надо написать в тексте буквы sp и любое число. Например: sp1</div>
        </div>
    </div>
    <div id="spaces">
        <?php
        if(!($model->isNewRecord && empty($model->Answers)))
        {
            foreach($model->Answers as $answer)
            {
                $this->renderPartial('visualizations/10_variant', array('model'=>$model, 'answer'=>$answer, 'index'=>$answer->number_space));
            }
        }
        ?>
    </div>
    <div id="hidden-options">
        <?php foreach($model->rightAnswersOrderSpace as $answer) : ?>
            <input data-index="<?php echo $answer->id ?>" type="hidden" name="Exercises[answers][<?php echo $answer->id ?>][answer]" value="<?php echo $answer->answer; ?>">
        <?php endforeach; ?>
    </div>
</div>
