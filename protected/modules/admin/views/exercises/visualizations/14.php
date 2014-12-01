<script>
    $(function(){
        $('#exercises-form').submit(function(){
            $return = true;    
            areasCont = $('#areas');
            areasError = areasCont.find('> .errorMessage');
            areas = areasCont.find('.update-area');
            if(areas.length)
            {
                areas.each(function(n, area){
                    if(!checkArea(area))
                    {
                        $return = false;
                    }
                });
                areasError.html('');
            }
            else
            {
                areasError.html('Добавьте области');
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
        
        $('.update-area .delete').live('click', function(){
            if(confirm('Вы дествительно хотите удалить предмет ?'))
            {
                $(this).closest('.update-area').remove();
            }
            return false;
        });
        
        $('#add-bag .add-button').click(function(){
            bags = $('#bags-table table');
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
        
        $('.update-area').live('change', function(){
            checkArea(this);
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
    
    function createBag(index, name)
    {
        area = '<tr class="update-bag" data-index="'+index+'">';
            area += '<td><input class="form-control input-sm name" placeholder="Введите название мешка" type="text" value="'+name+'" name="Bags['+index+'][name]"><div class="errorMessage"></div></td>';
            area += '<td><input class="" type="file" name="Bags['+index+'][imageFile]"></td>';
            area += '<td><a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a></td>';
        area += '</tr>';
        return area;
    }
    
    function checkArea(item)
    {
        $returnItem = true;
        item = $(item);
        itemName = item.find('.name');
        itemNameError = itemName.siblings('.errorMessage');
        if(!itemName.val())
        {
            itemNameError.html('Введите название области');
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

<div id="hotmap-bags">
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
    
    <div class="row">
        <div id="bags-table" class="col-lg-6 col-md-6">
            <label for>Мешки</label>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="40%" class="button-column">Изображение</th>
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
        
        <div id="areas" class="col-lg-6 col-md-6">
            <label for>Области</label>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="40%">Мешок</th>
                        <th width="10%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($id_map) : ?>
                        <?php foreach($model->Map->Areas as $index => $area) : ?>
                            <tr class="update-area" data-index="<?php echo $index; ?>">
                                <td>
                                    <input class="form-control input-sm name" placeholder="Введите название области" type="text" value="<?php echo $area->name; ?>" name="Exercises[answers][<?php echo $index; ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $area->id; ?>" name="Exercises[answers][<?php echo $index; ?>][id_area]">
                                    <div class="errorMessage"></div>
                                </td>
                                <td>
                                    <?php echo CHtml::dropDownList("Exercises[answers][$index][answer]", '', $listDataBags, array('class'=>'form-control input-sm id_bag', 'empty'=>'Выберите мешок')); ?>
                                    <div class="errorMessage"></div>
                                </td>
                                <td>
                                    <a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php foreach($model->Answers as $answer) : ?>
                            <tr class="update-area" data-index="<?php echo $answer->id; ?>">
                                <td>
                                    <input class="form-control input-sm name" placeholder="Введите название области" type="text" value="<?php echo $answer->name; ?>" name="Exercises[answers][<?php echo $answer->id ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $answer->id_area; ?>" name="Exercises[answers][<?php echo $answer->id ?>][id_area]">
                                    <div class="errorMessage"></div>
                                </td>
                                <td>
                                    <?php echo CHtml::dropDownList("Exercises[answers][$answer->id][answer]", $answer->answer, $listDataBags, array('class'=>'form-control input-sm id_bag', 'empty'=>'Выберите мешок')); ?>
                                    <div class="errorMessage"></div>
                                </td>
                                <td>
                                    <a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
    </div>
</div>
