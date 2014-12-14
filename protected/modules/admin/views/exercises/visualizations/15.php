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
                areasError.html('Нет ни одной области');
                $return = false;
            }
            
            return $return;
        });
        
        $('.update-area .delete').live('click', function(){
            if(confirm('Вы дествительно хотите удалить область из задания ?'))
            {
                $(this).closest('.update-area').remove();
            }
            return false;
        });
        
        $('.order-rows .glyphicon-arrow-up').live('click', function() {
            currentCriteria = $(this).closest('tr');
            upCriteria = currentCriteria.prev('tr');
            currentCriteria.after(upCriteria);
        });

        $('.order-rows .glyphicon-arrow-down').live('click', function() {
            currentCriteria = $(this).closest('tr');
            downCriteria = currentCriteria.next('tr');
            currentCriteria.before(downCriteria);
        });
        
        $('.update-area').live('change', function(){
            checkArea(this);
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
        
        return $returnItem;
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
        <div class="col-lg-3 col-md-3">
            <label for>Области</label>
        </div>
        <div id="areas" class="col-lg-5 col-md-5">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">Позиция</th>
                        <th width="90%">Название</th>
                        <th width="5%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($id_map) : ?>
                        <?php foreach($model->Map->Areas as $index => $area) : ?>
                            <tr class="update-area" data-index="<?php echo $index; ?>">
                                <td>
                                    <div class="order-rows">
                                        <i class="glyphicon glyphicon-arrow-up" title="переместить вверх"></i>
                                        <i class="glyphicon glyphicon-arrow-down" title="переместить вниз"></i>
                                    </div>
                                </td>
                                <td>
                                    <input class="form-control input-sm name" placeholder="Введите название области" type="text" value="<?php echo $area->name; ?>" name="Exercises[answers][<?php echo $index; ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $area->id; ?>" name="Exercises[answers][<?php echo $index; ?>][id_area]">
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
                                    <div class="order-rows">
                                        <i class="glyphicon glyphicon-arrow-up" title="переместить вверх"></i>
                                        <i class="glyphicon glyphicon-arrow-down" title="переместить вниз"></i>
                                    </div>
                                </td>
                                <td>
                                    <input class="form-control input-sm name" placeholder="Введите название области" type="text" value="<?php echo $answer->name; ?>" name="Exercises[answers][<?php echo $answer->id ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $answer->id_area; ?>" name="Exercises[answers][<?php echo $answer->id ?>][id_area]">
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
</div>
