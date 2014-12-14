<script>
    $(function(){
        $('#exercises-form').submit(function(){
            $return = true;    
            itemsCont = $('#items');
            itemsError = itemsCont.find('> .errorMessage');
            items = itemsCont.find('.update-item');
            if(items.length)
            {
                items.each(function(n, item){
                    if(!checkItems(item))
                    {
                        $return = false;
                    }
                });
                itemsError.html('');
            }
            else
            {
                itemsError.html('Добавьте предметы');
                $return = false;
            }
            return $return;
        });
        
        $('.update-item').live('change', function(){
            checkItems(this);
        });
        
        $('#add-button').click(function(){
            items = $('#items table');
            itemName = $('#add-item .name');
            itemNameError = itemName.siblings('.errorMessage');
            itemId_area = $('#add-item .id_area');
            itemId_areaError = itemId_area.siblings('.errorMessage');
            lastIndex = items.find('.update-item:last');
            index = lastIndex.length ? lastIndex.data('index')+1 : 0;
            error = false;
            if(!itemName.val())
            {
                itemNameError.html('Введите название предмета');
                error = true;
            }
            else
            {
                itemNameError.html('');
            }
            
            if(!itemId_area.val())
            {
                itemId_areaError.html('Выберите область');
                error = true;
            }
            else
            {
                itemId_areaError.html('');
            }
            
            if(!error)
            {
                items.append(createItem(index, itemName.val(), itemId_area.val()));
                itemName.val('');
                itemId_area.find('option[selected=selected]').removeAttr('selected');
            }
            return false;
        });
        
        $('.update-item .delete').live('click', function(){
            if(confirm('Вы дествительно хотите удалить предмет ?'))
            {
                $(this).closest('.update-item').remove();
            }
            return false;
        });
        
        $('.update-item .no-image').live('click', function(){
            current = $(this);
            current.siblings('input[type=file]').removeClass('hide');
            current.remove();
            return false;
        });
        
        $('.update-item .remove-image').live('click', function(){
            current = $(this);
            if(confirm("Картинка будет удалена при сохранении задания, продолжить ?"))
            {   
                id_answer = current.closest('.update-item').data('index');
                current.after('<input type="hidden" name="Exercises[answers]['+id_answer+'][deleteImage]" value="1">');
                current.siblings('input[type=file]').removeClass('hide');
                current.siblings('a').remove();
                current.remove();
            }
            return false;
        });
        
        <?php if($model->isNewRecord) : ?>
            $('.pick-map a').click(function(){
                current = $(this);
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('/admin/exercises/saveParams', array('id_visual'=>$model->id_visual)); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#exercises-form').serialize(),
                    success: function(result) {
                        if(result.success)
                        {
                            document.location.replace(current.attr('href'));
                            //alert(result.html);
                        }
                    }
                });
                return false;
            });
        <?php endif; ?>
    });
    
    function createItem(index, name, id_item)
    {
        item = '<tr class="update-item" data-index="'+index+'">';
            item += '<td><input class="form-control input-sm name" placeholder="Введите название предмета" type="text" value="'+name+'" name="Exercises[answers]['+index+'][name]"><div class="errorMessage"></div></td>';
            item += '<td><select class="form-control input-sm id_area" name="Exercises[answers]['+index+'][answer]">';
            options = $('#add-item .id_area option');
            options.each(function(n, option){
                option = $(option);
                if(option.val()==id_item)
                    $(option).attr('selected', 'selected');
                item += $(option).prop('outerHTML');
            });
            item += '</select><div class="errorMessage"></div></td>';
            item += '<td><input class="" type="file" name="Exercises[answers]['+index+'][imageFile]"></td>';
            item += '<td><a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a></td>';
        item += '</tr>';
        return item;
    }
    
    function checkItems(item)
    {
        $return = true;
        item = $(item);
        itemName = item.find('.name');
        itemNameError = itemName.siblings('.errorMessage');
        if(!itemName.val())
        {
            itemNameError.html('Введите название');
            $return = false;
        }
        else
        {
            itemNameError.html('');
        }

        itemArea = item.find('.id_area');
        itemAreaError = itemArea.siblings('.errorMessage');
        if(!itemArea.val())
        {
            itemAreaError.html('Выберите область');
            $return = false;
        }
        else
        {
            itemAreaError.html('');
        }
        return $return;
    }
</script>

<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo CHtml::label('Карта', ''); ?>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php 
            if($model->Map)
            {
                echo CHtml::link($model->Map->name, array('maps/update', 'id'=>$model->Map->id), array('target'=>'_blank'));
                echo CHtml::hiddenField('Exercises[id_map]', $model->Map->id);
            }
            else
                echo 'Не выбрана';
        ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-5 col-md-5 pick-map">
        <?php
            $pickMapAttrs = array('maps/index');
            $createMapAttrs = array('maps/create');
            if($model->isNewRecord)
            {
                $pickMapAttrs['id_visual'] = $model->id_visual;
                $createMapAttrs['id_visual'] = $model->id_visual;
            }
            else
            {
                $pickMapAttrs['id_exercise'] = $model->id;
                $createMapAttrs['id_exercise'] = $model->id;
            }
            
            if($id_group)
            {
                $pickMapAttrs['id_group'] = $id_group;
                $createMapAttrs['id_group'] = $id_group;
            }
            
            if($id_part)
            {
                $pickMapAttrs['id_part'] = $id_part;
                $createMapAttrs['id_part'] = $id_part;
            }
        ?>
        <?php echo CHtml::link('Выбрать карту<i class="glyphicon glyphicon-arrow-right"></i>', $pickMapAttrs, array('class'=>'btn btn-sm btn-success btn-icon-right')); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Создать новую карту', $createMapAttrs, array('class'=>'btn btn-sm btn-success btn-icon')); ?>
    </div>
</div>

<div id="hotmap-items">
    <?php $dataAreas = CHtml::listData($model->Map->Areas, 'id', 'name'); ?>
    <div class="row">
        <div class="col-lg-4 col-md-4">
            <label for="areas">Объекты маски</label>
            <?php echo CHtml::dropDownList('areas', '', $dataAreas, array('id'=>'areas', 'class'=>'form-control', 'size'=>2)); ?>
            <div class="errorMessage"></div>
        </div>
        <div id="items" class="col-lg-8 col-md-8">
            <label for="items">Предметы</label>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="30%">Область</th>
                        <th width="10%">Изображение</th>
                        <th width="10%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($model->Answers as $answer) : ?>
                        <tr class="update-item" data-index="<?php echo $answer->id; ?>">
                            <td>
                                <input class="form-control input-sm name" placeholder="Введите название предмета" type="text" value="<?php echo $answer->name; ?>" name="Exercises[answers][<?php echo $answer->id ?>][name]">
                                <div class="errorMessage"></div>
                            </td>
                            <td>
                                <?php echo CHtml::dropDownList("Exercises[answers][$answer->id][answer]", $answer->answer, $dataAreas, array('class'=>'form-control input-sm id_area', 'empty'=>'Выберите область')); ?>
                                <div class="errorMessage"></div>
                            </td>
                            <td>
                                <?php echo $answer->imageContainer; ?>
                                <div class="errorMessage"></div>
                            </td>
                            <td>
                                <a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="errorMessage"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-offset-4 col-md-offset-4 col-lg-8 col-md-8">
            <div class="row" id="add-item">
                <div class="col-lg-5 col-md-5">
                    <?php echo CHtml::textField('', '', array('class'=>'form-control input-sm name', 'placeholder'=>'Введите название предмета')); ?>
                    <div class="errorMessage"></div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::dropDownList('', '', $dataAreas, array('id'=>false, 'class'=>'form-control input-sm id_area', 'empty'=>'Выберите область')); ?>
                    <div class="errorMessage"></div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <?php echo CHtml::link('Добавить предмет', '#', array('id'=>'add-button', 'class'=>'btn btn-sm btn-success')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
