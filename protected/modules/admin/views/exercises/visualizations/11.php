<script>
    $(function(){
        $('#exercises-form').submit(function(){
            $return = true;    
            answer = $('#select-area');
            if(!answer.val())
            {
                answer.siblings('.errorMessage').html('Выберите правильный ответ');
                $return = false;
            } else {
                answer.siblings('.errorMessage').html('');
            }
            return $return;
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
<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo CHtml::label('Объекты маски', ''); ?>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::dropDownList('Exercises[answers][]', $model->idsRightAreas, CHtml::listData($model->Map->Areas, 'id', 'name'), array('class'=>'form-control', 'empty'=>'Выберите правильный ответ', 'id'=>'select-area')); ?>
        <div class="errorMessage"></div>
        <div id="hidden-options">
            <?php foreach($model->idsRightAreas as $id_area) : ?>
                    <input data-index="<?php echo $id_area; ?>" type="hidden" name="Exercises[answers][<?php echo $id_area; ?>][is_right]" value="1">
            <?php endforeach; ?>
        </div>
    </div>
</div>
