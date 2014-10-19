<script>
    $(function(){
        $('#add-area .add-button').click(function(){
            areas = $('#areas');
            areaName = $('#add-area .name');
            areaNameError = areaName.siblings('.errorMessage');
            areaId_bag = $('#add-area .id_bag');
            areaId_bagError = areaId_bag.siblings('.errorMessage');
            lastIndex = areas.find('.update-area:last');
            index = lastIndex.length ? lastIndex.data('index')+1 : 0;
            error = false;
            if(!areaName.val())
            {
                areaNameError.html('Введите название предмета');
                error = true;
            }
            else
            {
                areaNameError.html('');
            }
            
            if(!areaId_bag.val())
            {
                areaId_bagError.html('Выберите область');
                error = true;
            }
            else
            {
                areaId_bagError.html('');
            }
            
            if(!error)
            {
                areas.append(createItem(index, areaName.val(), areaId_bag.val()));
                areaName.val('');
                areaId_bag.val('');
            }
            return false;
        });
        
        $('.update-area .delete').live('click', function(){
            if(confirm('Вы дествительно хотите удалить предмет ?'))
            {
                $(this).closest('.update-area').remove();
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
    
    function createItem(index, name, id_area)
    {
        area = '<tr class="update-area" data-index="'+index+'">';
            area += '<td><input class="form-control input-sm" placeholder="Введите название предмета" type="text" value="'+name+'" name="Exercises[answers]['+index+'][name]"></td>';
            area += '<td><select class="form-control input-sm id_bag" name="Exercises[answers]['+index+'][answer]">';
            options = $('#add-area .id_bag option');
            options.each(function(n, option){
                option = $(option);
                if(option.val()==id_area)
                    $(option).attr('selected', 'selected');
                area += $(option).prop('outerHTML');
            });
            area += '</select></td>';
            area += '<td><a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a></td>';
        area += '</tr>';
        return area;
    }
    
    function createBag(index, name)
    {
        area = '<tr class="update-bag" data-index="'+index+'">';
            area += '<td><input class="form-control input-sm name" placeholder="Введите название мешка" type="text" value="'+name+'" name="Bags['+index+'][name]"></td>';
            area += '<td><a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a></td>';
        area += '</tr>';
        return area;
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
    <?php if($model->isNewRecord) : ?>
        <div class="row">
            <div class="col-lg-offset-3 col-md-offset-3 col-lg-5 col-md-5">
                <?php echo CHtml::link('Выбрать карту<i class="glyphicon glyphicon-arrow-right"></i>', array('maps/index', 'id_visual'=>$model->id_visual), array('class'=>'btn btn-sm btn-success btn-icon-right')); ?>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Создать новую карту', array('maps/create'), array('class'=>'btn btn-sm btn-success btn-icon')); ?>
            </div>
        </div>
    <?php endif; ?>
    
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
            <label for="areas">Области</label>
            <table id="areas" class="table table-hover">
                <thead>
                    <tr>
                        <th width="50%">Название</th>
                        <th width="40%">Мешок</th>
                        <th width="10%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($model->isNewRecord) : ?>
                        <?php foreach($model->Map->Areas as $index => $area) : ?>
                            <tr class="update-area" data-index="<?php echo $index; ?>">
                                <td>
                                    <input class="form-control input-sm" placeholder="Введите название области" type="text" value="<?php echo $area->name; ?>" name="Exercises[answers][<?php echo $index; ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $area->id; ?>" name="Exercises[answers][<?php echo $index; ?>][id_area]">
                                </td>
                                <td>
                                    <?php echo CHtml::dropDownList("Exercises[answers][$index][answer]", '', '', array('class'=>'form-control input-sm id_bag', 'empty'=>'Выберите мешок')); ?>
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
                                    <input class="form-control input-sm" placeholder="Введите название области" type="text" value="<?php echo $answer->name; ?>" name="Exercises[answers][<?php echo $answer->id ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $answer->id_area; ?>" name="Exercises[answers][<?php echo $answer->id ?>][id_area]">
                                </td>
                                <td>
                                    <?php echo CHtml::dropDownList("Exercises[answers][$answer->id][answer]", $answer->answer, $listDataBags, array('class'=>'form-control input-sm id_bag', 'empty'=>'Выберите мешок')); ?>
                                </td>
                                <td>
                                    <a class="delete" title="Удалить"><img src="/images/grid-delete.png" alt="Удалить"></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
    </div>
</div>
