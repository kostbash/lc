<script>
    $('.delete_button').live('click', function(){
        result = confirm('Вы действительно ходите удалить первые блоки во ВСЕХ курсах?');
        if (result) {
            window.location.href = '/admin/courses/deleteblocks';
        }
        return false;
    });
</script>

<div class="page-header clearfix">
    <h2>Курсы</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', array('courses/create'), array('class'=>'btn btn-primary btn-icon')); ?>
<!--    --><?php //echo CHtml::link('<i class="glyphicon glyphicon-minus"></i>Удалить первые блоки', array('#'), array('style'=>'margin-right:20px','class'=>'btn btn-danger btn-icon delete_button')); ?>
</div>
<?php $this->widget('ZGridView', array(
	'id'=>'courses-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
                'countLessons',
		array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}{params}{delete}',
                    'buttons' => array(
                        'params' => array(
                            'label'=>'',
                            'url'=>'Yii::app()->createUrl("/admin/courses/params", array("id_course"=>"$data->id"))',
                            'options'=>array('class'=>'course-params-icon', 'title'=>'Параметры курса'),
                        ),
                    ),
		),
	),
        'itemsCssClass'=>'table table-hover',
)); ?>
