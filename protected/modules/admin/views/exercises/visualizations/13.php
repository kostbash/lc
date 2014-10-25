<script>
    $(function(){
        $('#add-item .add-button').click(function(){
            items = $('#items');
            itemName = $('#add-item .name');
            itemNameError = itemName.siblings('.errorMessage');
            itemId_bag = $('#add-item .id_bag');
            itemId_bagError = itemId_bag.siblings('.errorMessage');
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
            
            if(!itemId_bag.val())
            {
                itemId_bagError.html('Выберите область');
                error = true;
            }
            else
            {
                itemId_bagError.html('');
            }
            
            if(!error)
            {
                items.append(createItem(index, itemName.val(), itemId_bag.val()));
                itemName.val('');
                itemId_bag.find('option[selected=selected]').removeAttr('selected');
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
        
        $('#add-bag .add-button').click(function(){
            bags = $('#bags-table');
            bagName = $('#add-bag .name');
            bagNameError = bagName.siblings('.errorMessage');
            lastIndex = bags.find('.update-bag:last');
            index = lastIndex.length ? lastIndex.data('index')+1 : 0;
            error = false;
            if(!bagName.val())
            {
                bagNameError.html('Введите название мешка');
                error = true;
            }
            else
            {
                bagNameError.html('');
            }
            
            if(!error)
            {
                bags.append(createBag(index, bagName.val()));
                option = '<option value='+index+'>'+bagName.val()+'</option>';
                $('.id_bag').append(option);
                bagName.val('');
            }
            return false;
        });
        
        $('.update-bag .delete').live('click', function(){
            if(confirm('Вы дествительно хотите удалить мешок ?'))
            {
                bag = $(this).closest('.update-bag');
                $('.id_bag option[value="'+bag.data('index')+'"]').remove();
                bag.remove();
            }
            return false;
        });
        
        $('.update-bag').live('change', function(){
            current = $(this);
            index = current.data('index');
            name = current.find('.name').val();
            $('.id_bag option[value="'+index+'"]').html(name);
            return false;
        });
    });
    
    function createItem(index, name, id_item)
    {
        item = '<tr class="update-item" data-index="'+index+'">';
            item += '<td><input class="form-control input-sm" placeholder="Введите название предмета" type="text" value="'+name+'" name="Exercises[answers]['+index+'][name]"></td>';
            item += '<td><select class="form-control input-sm id_bag" name="Exercises[answers]['+index+'][answer]">';
            options = $('#add-item .id_bag option');
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
    
    function createBag(index, name)
    {
        item = '<tr class="update-bag" data-index="'+index+'">';
            item += '<td><input class="form-control input-sm name" placeholder="Введите название мешка" type="text" value="'+name+'" name="Bags['+index+'][name]"></td>';
            item += '<td><a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a></td>';
        item += '</tr>';
        return item;
    }
</script>

<div id="bags">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <label for="bags">Мешки</label>
            <table id="bags-table" class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="10%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $listDataBags = array(); ?>
                    <?php foreach($model->Bags as $bag) : $listDataBags[$bag->id] = $bag->name; ?>
                        <tr class="update-bag" data-index="<?php echo $bag->id; ?>">
                            <td>
                                <input class="form-control input-sm name" placeholder="Введите название мешка" type="text" value="<?php echo $bag->name; ?>" name="Bags[<?php echo $bag->id; ?>][name]">
                            </td>
                            <td>
                                <a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="col-lg-6 col-md-6">
            <label for="items">Предметы</label>
            <table id="items" class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="40%">Мешок</th>
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
                                <?php echo CHtml::dropDownList("Exercises[answers][$answer->id][answer]", $answer->answer, $listDataBags, array('class'=>'form-control input-sm id_bag', 'empty'=>'Выберите мешок')); ?>
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
        <div class="col-lg-6 col-md-6">
            <div class="row" id="add-bag">
                <div class="col-lg-8 col-md-8">
                    <?php echo CHtml::textField('', '', array('class'=>'form-control input-sm name', 'placeholder'=>'Введите название мешка')); ?>
                    <div class="errorMessage"></div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::link('Добавить мешок', '#', array('class'=>'btn btn-sm btn-primary add-button')); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="row" id="add-item">
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::textField('', '', array('class'=>'form-control input-sm name', 'placeholder'=>'Введите название предмета')); ?>
                    <div class="errorMessage"></div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::dropDownList('', '', $listDataBags, array('id'=>false, 'class'=>'form-control input-sm id_bag', 'empty'=>'Выберите мешок')); ?>
                    <div class="errorMessage"></div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <?php echo CHtml::link('Добавить предмет', '#', array('class'=>'btn btn-sm btn-success add-button')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
