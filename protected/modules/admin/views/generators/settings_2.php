<script type='text/javascript'>
    $(function(){
        
        $('#tags').change(function(){
                attrWords = $('[name^=Words]');
                $('#words-grid').yiiGridView('update', { data: attrWords.serialize() });
        });
        
        $('#list-dictionaries').change(function(){
            current = $(this);
            tags = $('#tags');
            tags.html('<option value="">Показать все теги</option>');
            if(current.val())
            {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generatorstags/listdatabydictionary'); ?>',
                    type: 'POST',
                    data: {id_dict: current.val()},
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                        {
                            tags.html(result.html);
                        }
                    }
                });
            }
            attrWords = $('[name^=Words]');
            $('#words-grid').yiiGridView('update', { data: attrWords.serialize() });
        });
        
        $('#GeneratorsTemplates_type_of_building').change(function(){
            current = $(this);
            if(current.val()==8 || current.val()==9 || current.val()==10 || current.val()==11)
            {
                $('.more-attrs').removeClass('hide');
            } else {
                $('.more-attrs').addClass('hide');
            }
        });
        
        $('#generate').click(function(){
            $('#template-form').submit();
            return false;
        });
        
        $('#mass-delete').click(function(){
            checked = $('.select-on-check:checked');
            if(checked.length)
            {
                $.ajax({
                     url: '<?php echo Yii::app()->createUrl('admin/generatorstemplates/massdeletewords', array('id_template'=>$generator->Template->id)); ?>',
                     type:'POST',
                     dataType: 'json',
                     data: checked.serialize(),
                     success: function(result) { 
                         if(result.success) {
                            checked.closest('tr').remove();
                         }
                     }
                });
            } else {
                alert("Не отмечено ни одного слова");
            }
            return false;
        });
    });
    
    function getTags(id, name, id_word)
    {
        result = '<div class="skill clearfix" data-id='+id+'>';
            result += '<p class="name">'+name+'</p>';
            result += '<p class="remove">&times;</p>';
            if(id_word)
                result += '<input type="hidden" value="'+id+'" name="GeneratorsWords['+id_word+'][TagsIds][]" />';
            else
                result += '<input class="new-word" type="hidden" value="'+id+'" name="GeneratorsWords[TagsIds][]" />';
        result += '</div>';
        return result;
    }
    
    function deleteWord() {
        current = $(this);
        if(confirm('Вы действительно хотите удалить данный элемент ?'))
        {
            $.ajax({
                 url: current.attr('href'),
                 type:'POST',
                 dataType: 'json',
                 success: function() { 
                    attrWords = $('[name^=Words]');
                    $('#words-grid').yiiGridView('update', { data: attrWords.serialize() });
                 }
             });
        }
        return false;
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
          <h3 class="head">Настройки</h3>
          <div class="row">
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label('Способ построения заданий', 'GeneratorsTemplates_type_of_building', array('style'=>'line-height: 18px')); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?php echo CHtml::dropDownList('GeneratorsTemplates[type_of_building]', $generator->Template->type_of_building, Generators::$typiesBuilding, array('class'=>'form-control')); ?>
                  <div class="errorMessage"></div>
              </div>
          </div>
          <div class="row more-attrs<?php if(!($generator->Template->type_of_building==8 or $generator->Template->type_of_building==9 or $generator->Template->type_of_building==10 or $generator->Template->type_of_building==11)) echo ' hide';  ?>">
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label('Кол-во заданий', 'GeneratorsTemplates_number_exercises'); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?php echo CHtml::textField('GeneratorsTemplates[number_exercises]', $generator->Template->number_exercises, array('class'=>'form-control', 'placeholder'=>'Введите кол-во заданий')); ?>
                  <div class="errorMessage"></div>
              </div>
          </div>
          <div class="row more-attrs<?php if(!($generator->Template->type_of_building==8 or $generator->Template->type_of_building==9 or $generator->Template->type_of_building==10 or $generator->Template->type_of_building==11)) echo ' hide';  ?>">
              <div class="col-lg-2 col-md-2">
                  <?php echo CHtml::label('Кол-во слов в задании', 'GeneratorsTemplates_number_words'); ?>
              </div>
              <div class="col-lg-6 col-md-6">
                  <?php echo CHtml::textField('GeneratorsTemplates[number_words]', $generator->Template->number_words, array('class'=>'form-control', 'placeholder'=>'Введите кол-во слов в задании')); ?>
                  <div class="errorMessage"></div>
              </div>
          </div>
    </div>
    
    <div class="section" id='words'>
        <h3 class="head">Выбранные слова</h3>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                  <?php echo CHtml::dropDownList('Words[id_dictionary]', '', GeneratorsDictionaries::getDataDicitionaries($generator->id), array('empty'=>'Показать все словари', 'class'=>'form-control input-sm', 'id'=>'list-dictionaries')); ?>
            </div>
            <div class="col-lg-3 col-md-3">
                  <?php echo CHtml::dropDownList('Words[idsTags]', '', array(), array('empty'=>'Показать все теги', 'class'=>'form-control input-sm', 'id'=>'tags')); ?>
            </div>
            <div class="col-lg-offset-2 col-md-offset-2 col-lg-4 col-md-4" style="text-align: right">
                <?php echo CHtml::link('<i class="glyphicon glyphicon-remove"></i>Удалить отмеченные', '#', array('class'=>'btn btn-danger btn-sm btn-icon', 'id'=>'mass-delete')); ?>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить слова', array('/admin/generators/dictionaries', 'id_gen'=>$generator->id), array('class'=>'btn btn-success btn-sm btn-icon')); ?>
            </div>
        </div>
        <?php
        $this->widget('ZGridView', array(
            'id' => 'words-grid',
            'ajaxType'=>'POST',
            'selectableRows' => 2,
            'summaryText'=>'<p style="margin-top: 5px; text-align: right;">Слов для генерации: {count}</p>',
            'enableSorting' => false,
            'dataProvider' => $words->search($generator->Template->id),
            'columns' => array(
                array(
                    'class' => 'CCheckBoxColumn',
                    'id' => 'checked',
                    'value' => '$data->id',
                    'htmlOptions' => array('width' => '2%'),
                ),
                array(
                    'name' => 'word',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name' => 'Dictionary.name',
                    'header'=>'Словарь',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name' => 'translate',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name'=>'description',
                    'type'=>'raw',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name' => 'tagsString',
                    'header' => 'Теги',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name'=>'imageWithoutUpload',
                    'type'=>'raw',
                    'htmlOptions' => array('width' => '10%'),
                ),
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{delete}',
                    'htmlOptions' => array('width' => '8%'),
                    'buttons'=>array(
                        'delete'=>array(
                            'url' => 'Yii::app()->createUrl("/admin/generatorsTemplates/deleteselectedword", array("id_word"=>$data->id, id_template=>"'. $generator->Template->id .'"))',
                            'click'=>'deleteWord',
                        ),
                    ),
                ),
                
                
            ),
        ));
        ?>
    </div>
</div>
<?php $this->endWidget(); ?>