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
        <?php echo CHtml::label('Объекты маски', ''); ?>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::dropDownList('Exercises[correct_answers]', $model->idsRightAreas, CHtml::listData($model->Map->Areas, 'id', 'name'), array('class'=>'form-control', 'size'=>2)); ?>
        <div class="errorMessage"></div>
        <div id="hidden-options">
            <?php foreach($model->idsRightAreas as $id_area) : ?>
                    <input data-index="<?php echo $id_area; ?>" type="hidden" name="Exercises[answers][<?php echo $id_area; ?>][is_right]" value="1">
            <?php endforeach; ?>
        </div>
    </div>
</div>
