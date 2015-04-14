<script>
    $(function(){
        $('#searchTags').keyup(function(e){
            current = $(this);
            if(e.keyCode==13)
            {
                name = $.trim(current.val());
                if(name)
                {
                    $.ajax({
                        url:'<?php echo Yii::app()->createUrl('admin/mapTags/createbyajax')?>',
                        type:'POST',
                        data: current.serialize(),
                        dataType: 'json',
                        success: function(result) {
                            if(result.success)
                            {
                                id = result.id;
                                if(id)
                                {
                                    name = result.name;
                                    skillsContainer = current.closest('#tags').find('.skills');
                                    skillExist = skillsContainer.find('.skill[data-id='+id+']');
                                    if(!skillExist.length)
                                        skillsContainer.append(getTags(id, name));
                                }
                                current.siblings('.input-group-btn').removeClass('open');
                                current.val('');
                            }
                            else
                            {
                                alert(result.errors);
                            }
                        }
                    });
                }
            }
            else
            {
                $.ajax({
                    url:'<?php echo Yii::app()->createUrl('admin/mapTags/tagsbyajax'); ?>',
                    type:'POST',
                    data: { term: current.val() },
                    dataType: 'json',
                    success: function(result) {
                        if(result.success)
                        {
                            current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                            current.siblings('.input-group-btn').find('.dropdown-menu').append(result.html);
                            current.siblings('.input-group-btn').addClass('open');
                        }
                    }
                });
            }
            return false;
        });

        $('#tags .dropdown-toggle').click(function(e){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/mapTags/tagsbyajax'); ?>',
                type:'POST',
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                    {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result.html);
                    }
                }
            });
        });
        

        $('#tags .dropdown-menu li').live('click', function(){
            current = $(this);
            id = current.data('id');
            if(id)
            {
                name = current.find('a').html();
                skillsContainer = current.closest('#tags').find('.skills');
                skillExist = skillsContainer.find('.skill[data-id='+id+']');
                if(!skillExist.length)
                    skillsContainer.append(getTags(id, name));
            }
            current.closest('.input-group-btn').removeClass('open');
            return false;
        });
        
        $('#tags .skill .remove').live('click', function(){
            tag = $(this).closest('.skill');
            if(tag.hasClass('exist'))
            {
                deletedTags = $('#deleted-tags');
                tagId = tag.data('id');
                deletedTags.append('<input type="hidden" name="DeletedTags[]" value="'+tagId+'" />');
            }
            tag.remove();
        });
        
        $('#add-area').click(function(){
            areas = $('#areas-fields');
            lastArea = areas.find('.area:last-child');
            index = lastArea.length ? parseInt(lastArea.data('index')+1) : 0;
            nextNumber = parseInt(areas.find('.area').length)+1;
            nameArea = 'Область ' + nextNumber;
            area = '<div class="row area" data-index="'+index+'">';
                area += '<div class="col-md-3 col-lg-3">';
                    area += '<input class="form-control" type="text" name="Areas['+index+'][name]" value="'+nameArea+'" placeholder="Введите название области" />';
                area += '</div>';
                area += '<div class="col-md-2 col-lg-2">';
                    area += '<select class="form-control" name="Areas['+index+'][shape]">';
                        <?php foreach(MapAreas::$shapesRus as $id_shape => $shape_name) : ?>
                            area += '<option value="<?php echo $id_shape; ?>"><?php echo $shape_name; ?></option>';
                        <?php endforeach; ?>
                    area += '</select>';
                area += '</div>';
                area += '<div class="col-md-6 col-lg-6">';
                    area += '<textarea class="form-control" name="Areas['+index+'][coords]" placeholder="Введите координаты"></textarea>';
                area +='</div>';
                area += '<div class="col-md-1 col-lg-1" style="text-align: right;">';
                    area += '<a class="btn btn-danger delete-area" href="#"><i class="glyphicon glyphicon-remove"></i></a>';
                area +='</div>';
            area +='</div>';
            areas.append(area);
            return false;
        });
        
        $('.delete-area').live('click', function(){
            current = $(this);
            if(confirm('Вы действительно хотите удалить область ?'))
            {
                deletedAreas = $('#deleted-areas');
                area = current.closest('.area');
                if(area.length)
                {
                    areaId = area.data('id');
                    if(areaId)
                        deletedAreas.append('<input type="hidden" name="DeletedAreas[]" value="'+areaId+'" />');
                    area.remove();
                }
            }
            return false;
        });
    });
    
    function getTags(id, name)
    {
        result = '<div class="skill clearfix" data-id='+id+'>';
            result += '<p class="name">'+name+'</p>';
            result += '<p class="remove">&times;</p>';
            result += '<input type="hidden" value="'+id+'" name="Tags[]" />';
        result += '</div>';
        return result;
    }
</script>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'maps-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
    <div class="row row-attr">
        <div class="col-lg-2 col-md-2">
            <?php echo $form->label($model,'name'); ?>
        </div>
        <div class="col-lg-6 col-md-6">
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>"Введите название карты")); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-lg-2 col-md-2">
            <label for="searchTags">Теги</label>
        </div>
        <div class="col-lg-6 col-md-6">
            <div id="tags">
                <div class="skills-mini">
                    <div class="skills">
                        <?php foreach($model->Tags as $tag) : ?>
                            <div class="skill exist clearfix" data-id="<?php echo $tag->id; ?>">
                                <p class="name"><?php echo $tag->name; ?></p>
                                <p class="remove">&times;</p>
                                <input type="hidden" value="<?php echo $tag->id; ?>" name="Tags[]" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="input-group mydrop">
                    <?php echo CHtml::textField("tagName", '', array('placeholder'=>'Введите название тега', 'class'=>'form-control input-sm', 'id'=>'searchTags', 'autocomplete'=>'off')); ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row-attr">
        <div><label for>Карта</label></div>
        <div class="row row-attr">
            <div class="col-lg-1 col-md-1">
                <?php echo CHtml::radioButton('Maps[is_link]', !$model->is_link, array('id'=>'Maps_is_link_false', 'value'=>0)); ?>
            </div>
            <div class="col-lg-3 col-md-3">
                <label for="Maps_is_link_false">Выберите изображение</label>
            </div>
            <div class="col-lg-3 col-md-3">
                <?php echo $form->fileField($model, 'imageFile'); ?>
                <?php echo $form->error($model,'imageFile'); ?>
            </div>
        </div>
        <div class="row row-attr">
            <div class="col-lg-1 col-md-1">
                <?php echo CHtml::radioButton('Maps[is_link]', $model->is_link, array('id'=>'Maps_is_link_true', 'value'=>1)); ?>
            </div>
            <div class="col-lg-2 col-md-2">
                <label for="Maps_is_link_true">Укажите ссылку</label>
            </div>
            <div class="col-lg-4 col-md-4">
                <?php echo CHtml::textField('Maps[url_image]', $model->is_link ? $model->url_image : '', array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>"Введите прямую ссылку на картинку")); ?>
                <?php if($model->is_link) echo $form->error($model,'url_image'); ?>
            </div>
        </div>
    </div>
    <?php if(!$model->isNewRecord) : ?>
        <div id="map-image">
            <img src="<?php echo $clone->mapImageLink; ?>" />
            <?php $imageSize = getimagesize($clone->getMapImageLink(true)); ?>
            <svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" width="<?php echo $imageSize[0]; ?>" height="<?php echo $imageSize[1]; ?>">
                <?php
                    foreach($model->Areas as $area)
                    {
                        $coords = $area->cleanCoords;
                        if($area->shape==1)
                        {
                            $shape = "<circle cx='{$coords['cx']}' cy='{$coords['cy']}' r='{$coords['r']}'></circle>";
                        }
                        elseif($area->shape==2)
                        {
                            $shape = "<rect x='{$coords['x']}' y='{$coords['y']}' width='{$coords['width']}' height='{$coords['height']}'></rect>";
                        }
                        elseif($area->shape==3)
                        {
                           $shape = "<polygon points='{$coords['points']}'></polygon>";
                        }
                        echo "<g data-index='$area->id'>$shape</g>";
                    }
                ?>
            </svg>
        </div>
        <div class="section">
            <div class="head row row-attr">
                <div class="col-md-9 col-lg-9">
                    <h3 style="margin:0;">Области</h3>
                </div>
                <div class="col-md-3 col-lg-3" style="text-align: right;">
                    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить область', '#', array('id'=>'add-area', 'class'=>'btn btn-success btn-icon')); ?>
                </div>
            </div>
            <div id="areas-fields">
                <?php foreach($model->Areas as $area) : ?>
                    <div class="row area" data-index="<?php echo $area->id; ?>" data-id="<?php echo $area->id; ?>">
                        <div class="col-md-3 col-lg-3">
                            <input class="form-control" type="text" name="Areas[<?php echo $area->id; ?>][name]" value="<?php echo $area->name; ?>" placeholder="Введите название области" />
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <?php echo CHtml::dropDownList("Areas[$area->id][shape]", $area->shape, MapAreas::$shapesRus, array('class'=>'form-control')); ?>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <textarea class="form-control" name="Areas[<?php echo $area->id; ?>][coords]" placeholder="Введите координаты"><?php echo $area->coords; ?></textarea>
                        </div>
                        <div class="col-md-1 col-lg-1" style="text-align: right;">
                            <?php echo CHtml::link('<i class="glyphicon glyphicon-remove"></i>', '#', array('class'=>'btn btn-danger delete-area')); ?>
                        </div>
                        <input type="hidden" name="Areas[<?php echo $area->id; ?>][id]" value="<?php echo $area->id; ?>" />
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="deleted-areas"></div>
            <div id="deleted-tags"></div>
        </div>
    <?php endif; ?>
<?php $this->endWidget(); ?>

</div><!-- form -->