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
            
            $('.variable:not(.dont-remove)').remove();

            if(data.length)
            {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generatorsTemplatesVariables/getHtmlVars'); ?>',
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
            template = /[^x\+\-\*\d\/\(\)\s]/gi;
            if (this.value.match(template))
                this.value = this.value.replace(template, '');
        });
        
        $('.condition input[name*=condition]').live('change keyup input click', function() {
            template = /[^x\+\-\*\d\/\(\)\s=mod]/gi;
            if (this.value.match(template))
                this.value = this.value.replace(template, '');
        });
        
        $('.only-number').live('change keyup input click', function() {
            if (this.value.match(/[^0-9]/g))
                this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        $('#add-condition').click(function(){
            current = $(this);
            current.addClass('disabled');
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/generatorsTemplatesConditions/getHtmlCondition'); ?>',
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
        
        $('#generate').click(function(){
            checkTemplate();
            if(checkTemplate() & checkNumberExercises() & checkVariables() & checkConditions())
            {
                $('#template-form').submit();
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
            errors.html('Введите шаблон');
            return false;
        } else {
            errors.html('');
        }
        return true;
    }
    
    function checkVariables() {
        variables = $('.variable');
        res = 1;
        variables.each(function(n, variable)
        {
            variable = $(variable);
            minVal = variable.find('input[name*=value_min]');
            errorMin = minVal.siblings('.errorMessage').html('Введите мин. значение');
            maxVal = variable.find('input[name*=value_max]');
            errorMax = maxVal.siblings('.errorMessage');
            if(!minVal.val()) {
                errorMin.html('Введите мин. значение');
                res = 0;
            } else {
                if(minVal.val() >= maxVal.val()) {
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
                if(minVal.val() >= maxVal.val()) {
                    errorMax.html('Макс. значение не может быть меньше или равно мин. значению');
                    res = 0;
                }
                else
                errorMax.html('');
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
        msg = 'Несуществует переменных: ';
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
        <?php echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>К блоку', array('/admin/groupofexercises/update', 'id'=>$group->id), array('class'=>'btn btn-success btn-icon btn-sm', 'style'=>'float:left; width: 12%;')); ?>
        <h2 style="width: 73%; margin: 0 1%; border-bottom: none; text-align: center">Генератор <?php echo $generator->name; ?></h2>
        <?php echo CHtml::link('Запустить<i class="glyphicon glyphicon-play"></i>', '#', array('id'=>'generate', 'class'=>'btn btn-success btn-icon-right btn-sm', 'style'=>'float:left; width: 12%;')); ?>
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
            </div>
          <div class="row">
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label('Шаблон выражения', 'GeneratorsTemplates_template'); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?php echo CHtml::textField("GeneratorsTemplates[template]", $generator->Template->template, array('maxlength'=>255, 'class'=>'form-control', 'placeholder' => 'Введите выражение')); ?>
                  <div class="errorMessage"></div>
              </div>
          </div>
    </div>
    <div class="section" id='variables'>
        <h3 class="head">Переменные</h3>
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
</div>
<?php $this->endWidget(); ?>