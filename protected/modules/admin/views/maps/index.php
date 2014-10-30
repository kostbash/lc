<div class="page-header clearfix">
    <h2>Карты</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', array('maps/create'), array('class'=>'btn btn-primary btn-icon')); ?>
</div>

<script>
$(function(){
    $('#search-form').change(function(){
        $('#maps-grid').yiiGridView('update', { data: $(this).serialize()});
    });
    
    $('#pick-map').click(function(){
        current = $(this);
        id_map = $('.select-on-check:checked').val();
        if(id_map)
        {
            document.location.href=current.attr('href')+"/id_map/"+id_map;
        }
        else
        {
            alert('Выберите карту');
        }
        return false;
    });
});
</script>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form',
	'method'=>'POST',
)); ?>
<div class="section">
    <div class="row row-attr">
       <div class="col-md-3">
            <?php echo $form->label($model,'id_tag'); ?>
            <?php echo $form->dropDownList($model,'id_tag', CHtml::listData(MapTags::model()->findAll(), 'id', 'name'), array("class"=>"form-control input-sm", 'empty'=>'Все')) ?>
	</div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php $this->widget('ZGridView', array(
	'id'=>'maps-grid',
	'dataProvider'=>$model->search(),
        'ajaxType'=>'POST',
	'columns'=>array(
                array(
                    'class' => 'CCheckBoxColumn',
                    'id' => 'checked',
                    'value' => '$data->id',
                    'htmlOptions' => array('width' => '2%'),
                ),
		'name',
		'tagsString',
                'countAreas',
                array(
                    'header'=>'Просмотр',
                    'type'=>'raw',
                    'value'=>'CHtml::link("Просмотр", "$data->mapImageLink", array("target"=>"_blank"))',
                ),
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{update}{delete}',
		),
	),
        'itemsCssClass'=>'table table-hover',
)); ?>

<?php 
    if($visual)
    {
        $linkAttrs = array('exercises/create', 'id_type'=>$visual->id_type, 'id_visual'=>$visual->id);
    }
    
    if($exercise)
    {
        $linkAttrs = array('exercises/update', 'id'=>$exercise->id);
    }
    
    if($id_group)
    {
        $linkAttrs['id_group'] = $id_group;
    }
    
    if($id_part)
    {
        $linkAttrs['id_part'] = $id_part;
    }
    
    if($visual or $exercise)
        echo CHtml::link('Выбрать', $linkAttrs, array('id'=>'pick-map', 'class'=>'btn btn-sm btn-success btn-icon-right'));
?>
