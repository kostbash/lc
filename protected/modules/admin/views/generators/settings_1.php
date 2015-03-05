<script type='text/javascript'>
    $(function(){
        $('#GeneratorsTemplates_template').change(function(){
            input = $(this);
            val = input.val();
            template = /x\d+/ig;
            data = new Array();
            i = 0;
            $('.variable').removeClass('dont-remove');
            while ( (res = template.exec(val)) != null )
            {
              variable = $('.variable .name[data-name='+res[0]+']');
              if(variable.length)
                  variable.closest('.variable').addClass('dont-remove');
              else
                  data[i++] = res[0];
            }

            input = $('#GeneratorsTemplates_correct_answers');
            val = input.val();
            while ( (res = template.exec(val)) != null )
            {
                variable = $('.variable .name[data-name='+res[0]+']');
                if(variable.length)
                    variable.closest('.variable').addClass('dont-remove');
                else
                    data[i++] = res[0];
            }
            
            $('.variable:not(.dont-remove)').remove();

            if(data.length)
            {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generatorstemplatesvariables/getHtmlVars'); ?>',
                    type: 'POST',
                    data: { names:data, lastNum: $('.variable:last-child').data('num') },
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                        {
                            $('#variables').append(result.html);
                        }
                    }
                });
            }
        });

        $('#GeneratorsTemplates_correct_answers').change(function(){
            input = $(this);
            val = input.val();
            template = /x\d+/ig;
            data = new Array();
            i = 0;
            $('.variable').removeClass('dont-remove');
            while ( (res = template.exec(val)) != null )
            {
                variable = $('.variable .name[data-name='+res[0]+']');
                if(variable.length)
                    variable.closest('.variable').addClass('dont-remove');
                else
                    data[i++] = res[0];
            }

            input = $('#GeneratorsTemplates_template');
            val = input.val();
            while ( (res = template.exec(val)) != null )
            {
                variable = $('.variable .name[data-name='+res[0]+']');
                if(variable.length)
                    variable.closest('.variable').addClass('dont-remove');
                else
                    data[i++] = res[0];
            }

            $('.variable:not(.dont-remove)').remove();

            if(data.length)
            {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generatorstemplatesvariables/getHtmlVars'); ?>',
                    type: 'POST',
                    data: { names:data, lastNum: $('.variable:last-child').data('num') },
                    dataType: 'json',
                    success: function(result) {
                        if(result.success)
                        {
                            $('#variables').append(result.html);
                        }
                    }
                });
            }
        });
        
        $('#GeneratorsTemplates_template').live('change keyup input click', function() {
            if(!$('#GeneratorsTemplates_separate_template_and_correct_answers').is(':checked'))
            {
                template = /[^x\+\-\*\d\/\(\)\smod]/gi;
                if (this.value.match(template))
                    this.value = this.value.replace(template, '');
            }
        });

        $('#GeneratorsTemplates_correct_answers').live('change keyup input click', function() {
            if(!$('#GeneratorsTemplates_separate_template_and_correct_answers').is(':checked'))
            {
                template = /[^x\+\-\*\d\/\(\)\smod]/gi;
                if (this.value.match(template))
                    this.value = this.value.replace(template, '');
            }
        });
        
        $('#GeneratorsTemplates_correct_answers, #option-name').live('change keyup input click', function() {
            if(!$('#GeneratorsTemplates_separate_template_and_correct_answers').is(':checked'))
            {
                template = /[^x\+\-\*\d\/\(\)\smod]/gi;
                if (this.value.match(template))
                    this.value = this.value.replace(template, '');
            }
        });
        
        $('.condition input[name*=condition]').live('change keyup input click', function() {
            template = /[^x\+\-\*\d\/\(\)\s=mod\<\>\&\|]/gi;
            if (this.value.match(template))
                this.value = this.value.replace(template, '');
        });
        
        $('.only-number').live('change keyup input click', function() {
            if (this.value.match(/[^0-9]/g))
                this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        $('.enumeration').live('change keyup input click', function() {
            if (this.value.match(/[^0-9 ,]/g))
                this.value = this.value.replace(/[^0-9 ,]/g, '');
        });
        
        $('.type-min-max').live('change', function(){
            current = $(this);
            variable = current.closest('.variable');
            enumeration  = variable.find('.enumeration');
            enumeration.attr('disabled', 'disabled');
            variable.find('input[name*=value_min], input[name*=value_max]').removeAttr('disabled');
        });
        
        $('.type-enumeration').live('change', function(){
            current = $(this);
            variable = current.closest('.variable');
            enumeration  = variable.find('.enumeration');
            enumeration.removeAttr('disabled');
            variable.find('input[name*=value_min], input[name*=value_max]').attr('disabled', 'disabled');
        });
        
        $('#add-condition').click(function(){
            current = $(this);
            current.addClass('disabled');
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/generatorstemplatesconditions/getHtmlCondition'); ?>',
                type: 'POST',
                data: { lastNum: $('.condition:last-child').data('num') },
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                    {
                        $('#conditions').append(result.html);
                        current.removeClass('disabled');
                    }
                }
            });
            return false;
        });
        
        $('.remove-condition').live('click', function(){
            $(this).closest('.condition').remove();
            return false;
        });
        
        $('#GeneratorsTemplates_number_exercises').change(function(){
            checkNumberExercises();
        });
        
        $('#GeneratorsTemplates_template').change(function(){
            checkTemplate();
        });
        
        $('#GeneratorsTemplates_correct_answers').change(function(){
            checkCorrectAnswers();
        });
        
        $('#generate').click(function(){
            checkTemplate();
            if(checkTemplate() & checkCorrectAnswers() & checkNumberExercises() & checkVariables() & checkConditions())
            {
                $('#template-form').submit();
            }
            return false;
        });
        
        $('#GeneratorsTemplates_separate_template_and_correct_answers').change(function(){
           current = $(this);
           if(current.is(':checked'))
           {
               $('#correct-answers-cont').show();
               $('#correct-answers-cont input').attr('name', 'GeneratorsTemplates[correct_answers]');
           } else {
               $('#correct-answers-cont').hide();
               $('#correct-answers-cont input').attr('name', '');
           }
        });
        
        $('#GeneratorsTemplates_id_visual').change(function(){
            vis = $('#visualization');
            current = $(this);
            separate = $('#separate');
            check = separate.find('input[type=checkbox]');
            correctAnswer = $('#correct-answers-cont');
            label = correctAnswer.find('label');
            vis.find('.row').remove();
            
            if(current.val()==16)
            {
                check.attr('checked', 'checked');
                separate.addClass('hide');
                label.html('Текст задания');
                correctAnswer.show();
            }
            else
            {
                separate.removeClass('hide');
                label.html('Правильный ответ');
                if(current.val()==2 || current.val()==3)
                {
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl('admin/generators/gethtmlvisual'); ?>',
                        data: { id_visual: $(this).val() },
                        type: 'POST',
                        dataType: 'json',
                        success: function(result) {
                            if(result.success)
                                vis.append(result.html).removeClass('hide');
                        }
                    });
                }
            }
        });
        
        $('.add-option').live('click', function(){
            option = $('#option-name');
            val = option.val();
            error = option.siblings('.errorMessage');
            if(val)
            {
                template = /x\d+/ig;
                msg = 'Не существует переменных: ';
                notExist = [];
                i = 0;
                while ( (value = template.exec(val)) != null )
                {
                  variable = $('.variable .name[data-name='+value[0]+']');
                  if(!variable.length)
                  {
                      notExist[i++] = value[0];
                  }
                }
                notExist = $.unique(notExist);
                if(notExist.length)
                    error.html(msg + notExist.join(', '));
                else
                {
                    lastOption = $('#WrongAnswers option:last-child');
                    maxIndex = lastOption.length ? parseInt(lastOption.val())+1 : 0;
                    $('#WrongAnswers').append('<option value='+maxIndex+'>'+ option.val() +'</option>');
                    $('#hidden-options').append('<input data-index="'+maxIndex+'" type="hidden" name="WrongAnswers[]" value="'+ option.val() +'" />');
                    option.val('');
                    error.html('');
                }
            } else {
                error.html('Введите шаблон нового варианта');
            }
            return false;
        });
        
        $('.delete-option').live('click', function(){
            if(confirm('Вы действительно хотите удалить вариант ответа ?'))
            {
                selected = $('#WrongAnswers option:selected');
                selected.each(function(n, answer){
                    $('#hidden-options input[data-index='+ $(answer).val() +']').remove();
                });
                selected.remove();
            }
            return false;
        });
    });
    
    function checkNumberExercises() {
        numberExercises = $('#GeneratorsTemplates_number_exercises');
        errors = numberExercises.siblings('.errorMessage');
        if(!numberExercises.val())
        {
            errors.html('Введите кол-во заданий');
            return false;
        } else {
            errors.html('');
        }
        return true;
    }

    function checkTemplate() {
        template = $('#GeneratorsTemplates_template');
        errors = template.siblings('.errorMessage');
        if(!template.val())
        {
            //errors.html('Введите шаблон');
            //return false;
            template.val(' ');
        } else {
            if(!$('#GeneratorsTemplates_separate_template_and_correct_answers').is(':checked'))
            {
                templateExp = /^[x\+\-\*\d\/\(\)\s]+$/i;
                if (!templateExp.test(template.val()))
                {
                    errors.html('Шаблон может содержать только числа, мат.операции, пробел и букву x');
                    return false;
                } else {
                    errors.html('');
                }
                    
            } else {
                errors.html('');
            }
        }
        return true;
    }
    
    function checkCorrectAnswers() {
        if($('#GeneratorsTemplates_separate_template_and_correct_answers').is(':checked'))
        {
            template = $('#GeneratorsTemplates_correct_answers');
            generator_id = $('#GeneratorsTemplates_id_visual :selected').attr('value');
            errors = template.siblings('.errorMessage');
            if(!template.val() && generator_id != 16)
            {
                errors.html('Введите шаблон');
                return false;
            } else {
                if(!$('#GeneratorsTemplates_separate_template_and_correct_answers').is(':checked'))
                {
                    templateExp = /^[x\+\-\*\d\/\(\)\smod]+$/i;
                    if (!templateExp.test(template.val()))
                    {
                        errors.html('Шаблон может содержать только числа, мат.операции, пробел и букву x');
                        return false;
                    } else {
                        errors.html('');
                    }
                }
            } 
        }
        return true;
    }
    
    function checkVariables() {
        variables = $('.variable');
        res = 1;
        variables.each(function(n, variable)
        {
            variable = $(variable);
            type = variable.find('input[name*=values_type]:checked').val();
            minVal = variable.find('input[name*=value_min]');
            errorMin = minVal.siblings('.errorMessage').html('Введите мин. значение');
            maxVal = variable.find('input[name*=value_max]');
            errorMax = maxVal.siblings('.errorMessage');
            values = variable.find('input[name$="[values]"]');
            errorValues = values.siblings('.errorMessage');
            if(type==1)
            {
                if(!minVal.val()) {
                    errorMin.html('Введите мин. значение');
                    res = 0;
                } else {
                    if(parseInt(minVal.val(),10) >= parseInt(maxVal.val(),10)) {
                        errorMin.html('Мин. значение не может быть больше или равно макс. значению');
                        res = 0;
                    }
                    else
                        errorMin.html('');
                }
                if(!maxVal.val()) {
                    errorMax.html('Введите макс. значение');
                    res = 0;
                } else
                    if(parseInt(minVal.val(),10) >= parseInt(maxVal.val(),10)) {
                        errorMax.html('Макс. значение не может быть меньше или равно мин. значению');
                        res = 0;
                    }
                    else
                    errorMax.html('');
                errorValues.html('');
            }
            else if(type==2)
            {
                if(!values.val()) {
                    errorValues.html('Введите значения переменной');
                    res = 0;
                } else {
                    errorValues.html('');
                }
                errorMin.html('');
                errorMax.html('');
            }
        });
        if(res) {
            return true;
        } else {
            return false;
        }
    }
    function checkConditions() {
        conditions = $('.condition input[name*=condition]');
        res = 1;
        template = /x\d+/ig;
        msg = 'Не существует переменных: ';
        notExist = [];
        conditions.each(function(n, condition)
        {
            condition = $(condition);
            val = condition.val();
            error = condition.siblings('.errorMessage');
            i = 0;
            while ( (value = template.exec(val)) != null )
            {
              variable = $('.variable .name[data-name='+value[0]+']');
              if(!variable.length)
              {
                  notExist[i++] = value[0];
                  res = 0;
              }
            }
            notExist = $.unique(notExist);
            if(notExist.length)
                error.html(msg + notExist.join(', '));
            else
                error.html('');
            notExist.length = 0;
        });
        
        if(res) {
            return true;
        } else {
            return false;
        }
    }
</script>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'template-form',
	'enableAjaxValidation'=>false,
)); ?>

<div id='generator-page'>
    <div class="page-header clearfix">
        <?php if($group) echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>К блоку', array('/admin/groupofexercises/update', 'id'=>$group->id), array('class'=>'btn btn-success btn-icon btn-sm', 'style'=>'float:left; width: 12%;')); ?>
        <h2 style="width: 73%; margin: 0 1%; border-bottom: none; text-align: center">Генератор <?php echo $generator->name; ?></h2>
        <?php echo CHtml::link('Запустить<i class="glyphicon glyphicon-play"></i>', '#', array('id'=>'generate', 'class'=>'btn btn-success btn-icon-right btn-sm', 'style'=>'float:right; width: 12%;')); ?>
    </div>

    <div class="section" id='setting'>
          <h3 class="head">Шаблон</h3>
          <div class="row">
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label('Число заданий', 'GeneratorsTemplates_number_exercises'); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?php echo CHtml::textField("GeneratorsTemplates[number_exercises]", $generator->Template->number_exercises, array('maxlength'=>11, 'class'=>'form-control only-number', 'placeholder' => 'Введите число заданий')); ?>
                  <div class="errorMessage"></div>
              </div>
              <div id="separate" class="col-lg-4 col-md-4<?php if($generator->Template->id_visual==16) echo ' hide'; ?>">
                  <?php echo CHtml::hiddenField("GeneratorsTemplates[separate_template_and_correct_answers]", 0, array('id'=>false)); ?>
                  <?php echo CHtml::checkBox("GeneratorsTemplates[separate_template_and_correct_answers]", $generator->Template->separate_template_and_correct_answers, array('style'=>'float: left; width: 5%;')); ?>
                  <?php echo CHtml::label("Раздельные шаблоны условия и правильного ответа", 'GeneratorsTemplates_separate_template_and_correct_answers', array('style'=>'font-size: 14px; line-height: 14px; float: left; width: 93%; margin-left: 2%;')); ?>
              </div>
            </div>
          <div class="row">
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label('Шаблон условия', 'GeneratorsTemplates_template'); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?$this->widget("application.modules.admin.widgets.ButtonsWidget");?>
                  <?php echo CHtml::textArea("GeneratorsTemplates[template]", $generator->Template->template, array('maxlength'=>255, 'class'=>'form-control', 'placeholder' => 'Введите выражение')); ?>
                  <div class="errorMessage"></div>
              </div>
          </div>
          <div class="row" id="correct-answers-cont" <?php if(!$generator->Template->separate_template_and_correct_answers) echo 'style="display: none"'; ?>>
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label($generator->Template->id_visual==16 ? 'Текст задания' : 'Правильный ответ', 'GeneratorsTemplates_correct_answers'); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?php echo CHtml::textField($generator->Template->separate_template_and_correct_answers ? "GeneratorsTemplates[correct_answers]" : "", $generator->Template->correct_answers, array('maxlength'=>255, 'class'=>'form-control', 'placeholder' => 'Введите выражение', 'id'=>'GeneratorsTemplates_correct_answers')); ?>
                  <div class="errorMessage"></div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label('Тип задания', 'GeneratorsTemplates_id_visual'); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?php echo CHtml::dropDownList("GeneratorsTemplates[id_visual]", $generator->Template->id_visual, Generators::getVisualsForGenerator2(), array('class'=>'form-control')); ?>
                  <div class="errorMessage"></div>
              </div>
          </div>
    </div>
    <div class="section" id='variables'>
        <h3 class="head">Переменные</h3>
        <div class="row" id="header-variables">
            <div class="col-lg-offset-2 col-md-offset-2 col-lg-2 col-md-2">
                <label>Минимальное значение</label>
            </div>
            <div class="col-lg-2 col-md-2">
                <label>Максимальное значение</label>
            </div>
            <div class="col-lg-2 col-md-2">
                <label>Перечисление значений</label>
            </div>
        </div>
        <?php
        if($generator->Template->Variables)
        {
            foreach($generator->Template->Variables as $numVar => $var)
            {
                echo $var->getHtml(++$numVar);
            }
        }
        ?>
    </div>

    <div class="section" id='conditions'>
      <h3 class="head">Условия</h3>
      <?php
      if($generator->Template->Conditions)
      {
          foreach($generator->Template->Conditions as $numCon => $condition)
          {
              echo $condition->getHtml(++$numCon);
          }
      }
      ?>
    </div>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить условие', '#', array('class'=>'btn btn-success btn-icon', 'id'=>'add-condition')); ?>
    <div class="section<?php if(!$generator->Template->id_visual || $generator->Template->id_visual==1 or $generator->Template->id_visual==16) echo ' hide'; ?>" id='visualization'>
        <h3 class="head">Шаблоны неправильных ответов</h3>
        <?php if($generator->Template->id_visual && $generator->Template->id_visual!=1 && $generator->Template->id_visual!=16) $this->renderPartial("visualizations/{$generator->Template->id_visual}", array('model'=>$generator->Template)); ?>
    </div>
</div>
<?php $this->endWidget(); ?>