<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/ckeditor/ckeditor.js"); ?>
<script type='text/javascript'>
    $(function(){
        $('#add-dictionary').click(function(){
            newDict = $('#new-dictionary');
            if(newDict.val())
            {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generators/adddictionary', array('id_gen'=>$generator->id)); ?>',
                    type: 'POST',
                    data: { name: newDict.val() },
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                        {
                            $('#list-dictionaries').append('<option value='+result.id+'>'+result.name+'</option>');
                            newDict.val("");
                        }
                    }
                });
            } else {
                alert("Введите название нового словаря");
            }
            return false;
        });
        
        $('#add-tag').click(function(){
            newTag = $('#new-tag');
            id_dict = $('#list-dictionaries').val();
            if(id_dict)
            {
                if(newTag.val())
                {
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl('admin/generatorstags/createbyajax'); ?>',
                        type: 'POST',
                        data: { name: newTag.val(), id_dict: id_dict },
                        dataType: 'json',
                        success: function(result) { 
                            if(result.success)
                            {
                                tags.append(result.html);
                                newTag.val("");
                            }
                        }
                    });
                } else {
                    alert("Введите название тега");
                }
            } else {
                alert("Выберите словарь");
            }
            return false;
        });
        
        $('#add-word').click(function(){
            attributes = $('.new-word');
            word = $('#new-word-word');
            translate = $('#new-word-translate');
            description = $('.new-word-description');
            tags = $('#new-word-tags');
            id_dict = $('#list-dictionaries').val();
            if(id_dict)
            {
                if(!word.val())
                {    
                    alert('Введите слово');
                    return false;
                }
                
                if(!translate.val())
                {    
                    alert('Введите перевод');
                    return false;
                }
                
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generatorswords/createbyajax'); ?>',
                    type: 'POST',
                    data: attributes.serialize()+"&id_dict="+id_dict,
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                        {
                            $('#words-grid').yiiGridView('update', { data: $('[name^=Words]').serialize() });
                            word.val('');
                            translate.val('');
                            description.val('');
                            description.siblings('.for-editor-field').html('Введите описание').css('color', '#999');
                            tags.find('.skill').remove();
                        } else {
                            alert(result.errors);
                        }
                    }
                });
                
            } else {
                alert("Выберите словарь");
            }
            return false;
        });
        
        $('#list-dictionaries').change(function(){
            current = $(this);
            selected = current.find(':selected');
            edit = $('#edit-dictionary');
            tags = $('#wordsTags');
            tags.html('<option value="">Показать все теги</option>');
            if(selected.val())
            {
                edit.val(selected.html());
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generatorstags/listdatabydictionary'); ?>',
                    type: 'POST',
                    data: {id_dict: selected.val()},
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                        {
                            tags.html(result.html);
                        }
                    }
                });
            } else {
                edit.val('');
            }
            $('#new-word-tags').html('');
            $('#selected-mass-tags').html('');
        });
        
        $('#edit-dictionary').change(function(){
            current = $(this);
            dict = $('#list-dictionaries :selected');
            if(dict.length && current.val())
            {
                data = {};
                data.attributes = {};
                data.id = dict.val();
                data.attributes.name = current.val();
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generators/editdictionary'); ?>',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                        {
                            dict.html(result.name);
                        }
                    }
                });
            }
        });
        
        $('#remove-dictionary').click(function(){
            id_dict = $('#list-dictionaries').val();
            if(!id_dict)
            {
                alert("Выберите словарь");
                return false;
            }
            if(confirm('Вы действительно хотите удалить словарь ?'))
            {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('admin/generators/deletedictionary'); ?>',
                    type:'POST',
                    dataType: 'json',
                    data: {id_dict: id_dict},
                    success: function(result) {
                       if(result.success)
                       {
                           $('#list-dictionaries :selected').remove();
                           $('#edit-dictionary').val("");
                           $('#words-grid').yiiGridView('update');
                       }
                    }
                });
            }
            return false;
        });
        
        $('[name^=Words]').change(function(){
                attrWords = $('[name^=Words]');
                $('#words-grid').yiiGridView('update', { data: attrWords.serialize() });
        });
        
        $('.mydrop input[name=term]').live("keyup", function() {
            current = $(this);
            
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/generatorstags/tagsofdictionary'); ?>',
                type:'POST',
                dataType: 'json',
                data: { term: current.val(), id_dict: $('#list-dictionaries').val() },
                success: function(result) {
                    if(result.success)
                    {
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result.html);
                        current.siblings('.input-group-btn').addClass('open');
                    }
                }
            });
            return false;
        });
        
        $('.mydrop .dropdown-toggle').live("click", function() {
            current = $(this);
            
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/generatorstags/tagsofdictionary'); ?>',
                type:'POST',
                dataType: 'json',
                data: { term: current.val(), id_dict: $('#list-dictionaries').val() },
                success: function(result) {
                    if(result.success)
                    {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result.html);
                    }
                }
            });
            return false;
        });

        $('.mydrop .dropdown-menu li').live('click', function(){
            current = $(this);
            id = current.data('id');
            if(id)
            {
                name = current.find('a').html();
                tagsContainer = current.closest('.mydrop').siblings('.skills');
                tagExist = tagsContainer.find('.skill[data-id='+id+']');
                if(!tagExist.length)
                {
                    tagsContainer.append( getTags(id, name, current.closest('tr').data('id')) );
                    current.closest('tr').find('.save-row').show();
                }
            }
            current.closest('.input-group-btn').removeClass('open');
            return false;
        });
        
            
        $('#words-grid tbody tr textarea, input[type=text]:not([name=term]), input[type=file], select').live('change', function(){
            $(this).closest('tr').find('.save-row').show();
        });
        
        $('.skill .remove').live('click', function(){
           if(confirm('Вы уверены, что хотите удалить данный тег ?'))
           {
                $(this).closest('tr').find('.save-row').show();
                $(this).closest('.skill').remove();
           }
           return false;
        });

        $('#words-grid .save-row').live('click', function(){
           current = $(this);
           $.ajax({
                url: current.attr('href'),
                type:'POST',
                dataType: 'json',
                data: current.closest('tr').find('textarea, input:not([name=term]), select').serialize(),
                success: function(result) { 
                    if(result.success) {
                        current.hide();
                    } else {
                        alert(result.errors);
                    }
                }
            });
            return false;
        });
        
        $('.for-editor-field').live('click', function() {
            current = $(this);
            hidden = current.siblings('input[type=hidden]');
            if(hidden.attr('id')!='editingQuestion')
            {
                $('#editingQuestion').removeAttr('id');
                hidden.attr('id', 'editingQuestion');
                CKEDITOR.instances['editor-text'].setData(hidden.val());
            }
            $('#htmlEditor').modal('show');
        });

        $('#amend').click(function() {
            data = $.trim(CKEDITOR.instances['editor-text'].getData());
            editing = $('#editingQuestion');
            if(editing.val()!=data)
            {
                editing.val(data);
                if(editing.hasClass('new-word'))
                {
                    if(data)
                    {
                        editing.siblings('.for-editor-field').html(data).css('color', '#555');
                    } else {
                        editing.siblings('.for-editor-field').html('Введите описание').css('color', '#999');
                    }
                } else {
                    editing.siblings('.for-editor-field').html(data).closest('tr').find('.save-row').show();
                }
            }
            $('#htmlEditor').modal('hide');
        });
        
        $('#add-mass-tags').click(function(){
            checked = $('input[type=checkbox][name="checked[]"]:checked');
            tags = $('#selected-mass-tags .skill');
            if(checked.length && tags.length)
            {
                checked.each(function(n, check)
                {
                    check = $(check);
                    tagsContainer = check.closest('tr').find('.skills');
                    tags.each(function(k, tag)
                    {
                        tag = $(tag);
                        tag_id = tag.data('id');
                        tag_name = tag.find('.name').html();
                        tagExist = tagsContainer.find('.skill[data-id='+tag_id+']');
                        if(!tagExist.length)
                        {
                            tagsContainer.append(getTags(tag_id, tag_name, check.val()));
                            check.closest('tr').find('.save-row').show();
                        }
                    });
                });
            }
            return false;
        });
        
        $('#add-selected-words').click(function(){
            checked = $('input[type=checkbox][name="checked[]"]:checked');
            if(checked.length)
            {
                $("#paramsSearch").submit();
            } else {
                alert('Не отмечено ни одно слово для добавления');
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
        'id'=>'paramsSearch',
	'method'=>'POST',
)); ?>
<script type="text/javascript" src="/js/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="/js/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="/js/swfupload/handlers.js"></script>
<script type="text/javascript" src="/js/uploader.js"></script>
<script src="/js/jquery.html5_upload.js" type="text/javascript"></script>
<div id="dictionary-page">
    <div class="modal fade" id="htmlEditor" tabindex="-1" role="dialog" aria-labelledby="htmlEditorLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="htmlEditorLabel">Html-редактор</h4>
          </div>
          <div class="modal-body">
              <textarea id="editor-text" class="ckeditor" name="editor"></textarea>
          </div>
          <div class="modal-footer">
            <?php echo CHtml::Button('Внести изменения', array("class"=>"btn btn-primary", 'id'=>'amend')); ?>
          </div>
        </div>
      </div>
    </div>
    
    <div class="page-header clearfix">
        <h2 style="width: 74%; border-bottom: none;">Добавление слов в набор</h2>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>Отмена', $_SESSION['returnUrl'], array('class'=>'btn btn-danger btn-icon btn-sm', 'style'=>'margin-left: 1%; float:left; width: 12%;')); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', '#', array('id'=>'add-selected-words', 'class'=>'btn btn-success btn-icon btn-sm', 'style'=>'margin-left: 1%; float:left; width: 12%;')); ?>
    </div>

    <div class="section">
          <h3 class="head">Словари</h3>
          <div class="row">
              <div class="col-lg-5 col-md-5">
                  <?php echo CHtml::label("Выберите словарь", "list-dictionaries"); ?><br>
                  <?php echo CHtml::dropDownList('Words[id_dictionary]', '', GeneratorsDictionaries::getDataDicitionaries($generator->id), array('empty'=>'Выберите словарь', 'class'=>'form-control input-sm', 'id'=>'list-dictionaries')); ?>
              </div>
              <div class="col-lg-5 col-md-5">
                  <?php echo CHtml::label("Добавить словарь", "new-dictionary"); ?><br>
                  <?php echo CHtml::textField("", "", array('maxlength'=>255, 'class'=>'form-control input-sm', 'style'=>'float: left; width: 75%;', 'placeholder' => 'Введите название нового словаря', 'id'=>'new-dictionary')); ?>
                  <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', '#', array('maxlength'=>11, 'class'=>'btn btn-success btn-icon btn-sm', 'style'=>'float: right; width: 24%', 'id'=>'add-dictionary')); ?>
              </div>
              <div class="col-lg-2 col-md-2" style="text-align: right;">
                  <?php echo CHtml::link('<i class="glyphicon glyphicon-remove"></i>Удалить словарь', '#', array('class'=>'btn btn-danger btn-icon btn-sm', 'id'=>'remove-dictionary', 'style'=>'margin-top: 32px')); ?>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-5 col-md-5">
                  <?php echo CHtml::label("Изменить название", "edit-dictionary"); ?><br>
                  <?php echo CHtml::textField("edit-dictionary", "", array('maxlength'=>255, 'class'=>'form-control input-sm', 'placeholder' => 'Введите название словаря')); ?>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-5 col-md-5">
                  <?php echo CHtml::label("Добавить тег", "new-tag"); ?><br>
                  <?php echo CHtml::textField("", "", array('maxlength'=>255, 'class'=>'form-control input-sm', 'style'=>'float: left; width: 75%;', 'placeholder' => 'Введите название тега', 'id'=>'new-tag')); ?>
                  <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', '#', array('class'=>'btn btn-success btn-icon btn-sm', 'style'=>'float: right; width: 24%', 'id'=>'add-tag')); ?>
              </div>
          </div>
    </div>
    <div class="section">
        <h3 class="head">Слова</h3>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <?php echo CHtml::textField("Words[word]", "", array('maxlength'=>255, 'class'=>'form-control input-sm', 'placeholder' => 'Введите первые буквы слова')); ?>
            </div>
            <div class="col-lg-3 col-md-3"  style="text-align: right;">
                  <?php echo CHtml::dropDownList('Words[idsTags]', '', array(), array('empty'=>'Показать все теги', 'class'=>'form-control input-sm', 'id'=>'wordsTags')); ?>
            </div>
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-3 col-md-3"  style="text-align: right;">
                <div class="skills-mini">
                    <div class="skills" id="selected-mass-tags"></div>
                    <div class="input-group mydrop">
                        <input placeholder="Введите название" class="form-control input-sm" autocomplete="off" data-id="1" type="text" value="" name="term">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1"><span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2"  style="text-align: right;">
                <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить отмеченным', '#', array('class'=>'btn btn-success btn-icon btn-sm', 'id'=>'add-mass-tags')); ?>
            </div>
        </div>
        <input type="hidden" name="checked" />
        <?php
        $this->widget('ZGridView', array(
            'id' => 'words-grid',
            'selectableRows' => null,
            'ajaxType'=>'POST',
            'afterAjaxUpdate'=>'function() {updateSWFUpload()}',
            'enableSorting' => false,
            'rowHtmlOptionsExpression' => 'array("data-id"=>"$data->id")',
            'dataProvider' => $words->search(),
            'columns' => array(
                array(
                    'class' => 'CCheckBoxColumn',
                    'id' => 'checked',
                    'selectableRows' => 2,
                    'value' => '$data->id',
                    'htmlOptions' => array('width' => '2%'),
                ),
                array(
                    'name' => 'word',
                    'type' => 'textField',
                    'htmlOptions' => array('width' => '20%'),
                ),

                array(
                    'name' => 'translate',
                    'type' => 'textArea',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name'=>'description',
                    'type' => 'forEditor',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name' => 'Tags',
                    'header' => 'Теги',
                    'type' => 'tags',
                    'htmlOptions' => array('width' => '20%'),
                ),
                array(
                    'name'=>'imageLinkWithUpload',
                    'type'=>'raw',
                    'htmlOptions' => array('width' => '10%'),
                ),
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{save}{delete}',
                    'htmlOptions' => array('width' => '8%'),
                    'buttons'=>array(
                        'delete'=>array(
                            'url' => 'Yii::app()->createUrl("/admin/generatorswords/delete", array("id"=>$data->id))',
                            'click'=>'deleteWord',
                        ),
                        'save'=>array(
                            'label'=>'<i class="glyphicon glyphicon-floppy-disk"></i>',
                            'url'=>'Yii::app()->createUrl("/admin/generatorswords/updatebyajax")',
                            'options'=>array('class'=>'save-row', 'title'=>'Сохранить изменения'),
                        ),
                    ),
                ),
                
                
            ),
        ));
        ?>
        <div class="section">
            <h3 class="head">Добавить слово в выбранный словарь</h3>
            <div class="clearfix">
                <div style="width: 21%; float: left; margin-right: 1%">
                    <input type="text" id="new-word-word" name="GeneratorsWords[word]" maxlength="255" class="form-control new-word input-sm" placeholder="Введите слово">
                </div>
                <div style="width: 21%; float: left; margin-right: 1%">
                    <textarea maxlength="255" id="new-word-translate" class="form-control new-word input-sm" rows="2" placeholder="Введите перевод" name="GeneratorsWords[translate]"></textarea>
                </div>
                <div style="width: 21%; float: left; margin-right: 1%">
                    <input type="hidden" value="" name="GeneratorsWords[description]" class="new-word new-word-description">
                    <div class="for-editor-field" style="font-size: 12px; color: #999; min-height: 30px" title="Нажмите, чтобы открыть редактор">Введите описание</div>
                </div>
                <div style="width: 21%; float: left; margin-right: 1%">
                    <div class="skills-mini">
                        <div class="skills" id="new-word-tags"></div>
                        <div class="input-group mydrop">
                            <input placeholder="Введите название" class="form-control input-sm" autocomplete="off" data-id="1" type="text" value="" name="term">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1"><span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width: 12%; float: right; text-align: right">
                    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить слово', '#', array('class'=>'btn btn-success btn-icon btn-sm', 'id'=>'add-word')); ?>
                </div>
                </table>
            </div>
        </div>
    </div>
</div>
<?php // Yii::app()->clientScript->registerScript('search' . $x . $this->id, '
//                        function uploadHandle()
//                        {
//                            var f = $(".upload-image"),
//			    up = new uploader(f.get(0), {
//				prefix:"ImportFile",
//				url:"' . Yii::app()->createUrl("/admin/exercises/SWFUpload", array('id_course'=>$id_course)) . '",
//				autoUpload:true,
//				error:function(ev){ console.log("error"); remove_veil(); $("#import-button").show();$("#import-input").hide();},
//				success:function(data){$(document.body).after(data);remove_veil();$("#import-button").show();$("#import-input").hide();}
//			    });
//                             
//                        }
//			$(function(){
//                            	uploadHandle();
//			});
//		    ');
$this->endWidget(); ?>
<script>
    function updateSWFUpload()
    {
        $('[class^="upload-image"]').each(function(i,e){
            var id = $(e).data('id'); 
            var f = $('.upload-image'+id),
                up = new uploader(f.get(0), {
                    prefix:'ImportFile',
                    url:'<?php echo Yii::app()->createUrl("/admin/generatorswords/SWFUpload/id_word/") ?>/'+id,
                    autoUpload:true,
                    error:function(ev){console.log('error'); remove_veil(); $('#import-button'+id).show();$('#import-input'+id).hide();},
                    success:function(data){$('.word-image-container'+id).html(data);remove_veil();}
                });
        });
        
    }
    updateSWFUpload();
</script>
