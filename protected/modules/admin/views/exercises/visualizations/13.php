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
            
            bagsCont = $('#bags-table');
            bagsError = bagsCont.find('> .errorMessage');
            bags = bagsCont.find('.update-bag');
            
            if(bags.length)
            {
                bags.each(function(n, bag){
                    if(!checkBag(bag))
                    {
                        $return = false;
                    }
                });
                bagsError.html('');
            }
            else
            {
                bagsError.html('Добавьте мешки');
                $return = false;
            }
            
            return $return;
        });
        
        $('.update-item').live('change', function(){
            checkItems(this);
        });
        
        $('#add-item .add-button').click(function(){
            itemsCont = $('#items');
            itemName = $('#add-item .name');
            itemNameError = itemName.siblings('.errorMessage');
            itemId_bag = $('#add-item .id_bag');
            itemId_bagError = itemId_bag.siblings('.errorMessage');
            lastIndex = itemsCont.find('.update-item:last');
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
                itemId_bagError.html('Выберите мешок');
                error = true;
            }
            else
            {
                itemId_bagError.html('');
            }
            
            if(!error)
            {
                itemsCont.find('> table').append(createItem(index, itemName.val(), itemId_bag.val()));
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
            bags = $('#bags-table > table');
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
            checkBag(this);
            return false;
        });
        
        $('.update-bag .no-image').live('click', function(){
            current = $(this);
            current.siblings('input[type=file]').removeClass('hide');
            current.remove();
            return false;
        });
        
        $('.update-bag .remove-image').live('click', function(){
            current = $(this);
            if(confirm("Картинка будет удалена при сохранении задания, продолжить ?"))
            {   
                id_bag = current.closest('.update-bag').data('index');
                current.after('<input type="hidden" name="Bags['+id_bag+'][deleteImage]" value="1">');
                current.siblings('input[type=file]').removeClass('hide');
                current.siblings('a').remove();
                current.remove();
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
    });
    
    function createItem(index, name, id_item)
    {
        item = '<tr class="update-item" data-index="'+index+'">';
            item += '<td><input class="form-control input-sm name" placeholder="Введите название предмета" type="text" value="'+name+'" name="Exercises[answers]['+index+'][name]"><div class="errorMessage"></div></td>';
            item += '<td><select class="form-control input-sm id_bag" name="Exercises[answers]['+index+'][answer]">';
            options = $('#add-item .id_bag option');
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
    
    function createBag(index, name)
    {
        item = '<tr class="update-bag" data-index="'+index+'">';
            item += '<td><input class="form-control input-sm name" placeholder="Введите название мешка" type="text" value="'+name+'" name="Bags['+index+'][name]"><div class="errorMessage"></div></td>';
            item += '<td><input class="" type="file" name="Bags['+index+'][imageFile]"></td>';
            item += '<td><a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a></td>';
        item += '</tr>';
        return item;
    }
    
    function checkItems(item)
    {
        $returnItem = true;
        item = $(item);
        itemName = item.find('.name');
        itemNameError = itemName.siblings('.errorMessage');
        if(!itemName.val())
        {
            itemNameError.html('Введите название предмета');
            $returnItem = false;
        }
        else
        {
            itemNameError.html('');
        }

        itemBag = item.find('.id_bag');
        itemBagError = itemBag.siblings('.errorMessage');
        if(!itemBag.val())
        {
            itemBagError.html('Выберите мешок');
            $returnItem = false;
        }
        else
        {
            itemBagError.html('');
        }
        return $returnItem;
    }
    
    function checkBag(bag)
    {
        $returnBag = true;
        bag = $(bag);
        bagName = bag.find('.name');
        bagNameError = bagName.siblings('.errorMessage');
        if(!bagName.val())
        {
            bagNameError.html('Введите название мешка');
            $returnBag = false;
        }
        else
        {
            bagNameError.html('');
        }
        return $returnBag;
    }
</script>

<div id="bags">
    <div class="row">
        <div id="bags-table" class="col-lg-6 col-md-6">
            <label for>Мешки</label>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="10%">Изображение</th>
                        <th width="10%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $listDataBags = array(); ?>
                    <?php foreach($model->Bags as $bag) : $listDataBags[$bag->id] = $bag->name; ?>
                        <tr class="update-bag" data-index="<?php echo $bag->id; ?>">
                            <td>
                                <input class="form-control input-sm name" placeholder="Введите название мешка" type="text" value="<?php echo $bag->name; ?>" name="Bags[<?php echo $bag->id; ?>][name]">
                                <div class="errorMessage"></div>
                            </td>
                            <td>
                                <?php echo $bag->imageContainer; ?>
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
        
        <div id="items" class="col-lg-6 col-md-6">
            <label for>Предметы</label>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="30%">Мешок</th>
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
                                <?php echo CHtml::dropDownList("Exercises[answers][$answer->id][answer]", $answer->answer, $listDataBags, array('class'=>'form-control input-sm id_bag', 'empty'=>'Выберите мешок')); ?>
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
