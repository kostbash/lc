<script>
    $(function(){
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
    });
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
        <div class="col-lg-3 col-md-3">
            <label for="areas">Области</label>
        </div>
        <div class="col-lg-5 col-md-5">
            <table id="areas" class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">Позиция</th>
                        <th width="90%">Название</th>
                        <th width="5%" class="button-column">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($model->isNewRecord) : ?>
                        <?php foreach($model->Map->Areas as $index => $area) : ?>
                            <tr class="update-area" data-index="<?php echo $index; ?>">
                                <td>
                                    <div class="order-rows">
                                        <i class="glyphicon glyphicon-arrow-up" title="переместить вверх"></i>
                                        <i class="glyphicon glyphicon-arrow-down" title="переместить вниз"></i>
                                    </div>
                                </td>
                                <td>
                                    <input class="form-control input-sm" placeholder="Введите название области" type="text" value="<?php echo $area->name; ?>" name="Exercises[answers][<?php echo $index; ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $area->id; ?>" name="Exercises[answers][<?php echo $index; ?>][id_area]">
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
                                    <input class="form-control input-sm" placeholder="Введите название области" type="text" value="<?php echo $answer->name; ?>" name="Exercises[answers][<?php echo $answer->id ?>][name]">
                                    <input class="form-control input-sm" type="hidden" value="<?php echo $answer->id_area; ?>" name="Exercises[answers][<?php echo $answer->id ?>][id_area]">
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
</div>
