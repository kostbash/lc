<script>
    $(function(){
        $('#add-button').click(function(){
            items = $('#items');
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
                itemId_area.val('');
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
    });
    
    function createItem(index, name, id_item)
    {
        item = '<tr class="update-item" data-index="'+index+'">';
            item += '<td><input class="form-control input-sm" placeholder="Введите название предмета" type="text" value="'+name+'" name="Exercises[answers]['+index+'][name]"></td>';
            item += '<td><select class="form-control input-sm" name="Exercises[answers]['+index+'][answer]">';
            options = $('#add-item .id_area option');
            options.each(function(n, option){
                option = $(option);
                if(option.val()==id_item)
                    $(option).attr('selected', 'selected');
                item += $(option).prop('outerHTML');
            });
            item += '</select></td>';
            item += '<td><a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a></td>';
        item += '</tr>';
        return item;
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
<?php if($model->isNewRecord) : ?>
<div class="row">
    <div class="col-lg-offset-3 col-md-offset-3 col-lg-5 col-md-5">
        <?php echo CHtml::link('Выбрать карту<i class="glyphicon glyphicon-arrow-right"></i>', array('maps/index', 'id_visual'=>$model->id_visual), array('class'=>'btn btn-sm btn-success btn-icon-right')); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Создать новую карту', array('maps/create'), array('class'=>'btn btn-sm btn-success btn-icon')); ?>
    </div>
</div>
<?php endif; ?>

<div id="hotmap-items">
    <?php $dataAreas = CHtml::listData($model->Map->Areas, 'id', 'name'); ?>
    <div class="row">
        <div class="col-lg-4 col-md-4">
            <label for="areas">Объекты маски</label>
            <?php echo CHtml::dropDownList('areas', '', $dataAreas, array('id'=>'areas', 'class'=>'form-control', 'size'=>2)); ?>
            <div class="errorMessage"></div>
        </div>
        <div class="col-lg-8 col-md-8">
            <label for="items">Предметы</label>
            <table id="items" class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="40%">Область</th>
                        <th width="10%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($model->Answers as $answer) : ?>
                        <tr class="update-item" data-index="<?php echo $answer->id; ?>">
                            <td>
                                <input class="form-control input-sm" placeholder="Введите название предмета" type="text" value="<?php echo $answer->name; ?>" name="Exercises[answers][<?php echo $answer->id ?>][name]">
                            </td>
                            <td>
                                <?php echo CHtml::dropDownList("Exercises[answers][$answer->id][answer]", $answer->answer, $dataAreas, array('class'=>'form-control input-sm', 'empty'=>'Выберите область')); ?>
                            </td>
                            <td>
                                <a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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