<div class="page-header clearfix">
    <h2>Карты</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', array('maps/create'), array('class'=>'btn btn-primary btn-icon')); ?>
</div>

<script>
$(function(){
    $('#search-form').change(function(){
        $('#maps-grid').yiiGridView('update', { data: $(this).serialize()});
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
		'name',
		'tagsString',
                'countAreas',
                array(
                    'header'=>'Просмотр',
                    'type'=>'raw',
                    'value'=>'CHtml::link("Просмотр", "$data->MapImageLink", array("target"=>"_blank"))',
                ),
		array(
			'class'=>'CButtonColumn',
                        'template'=>'{update}{delete}',
		),
	),
        'itemsCssClass'=>'table table-hover',
)); ?>
