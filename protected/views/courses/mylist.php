<div class="page-header clearfix">
    <h2>Курсы</h2>
</div>
<?php $this->widget('ZGridView', array(
	'id'=>'courses-grid',
	'dataProvider'=>$model->searchUserCourses(),
	'columns'=>array(
                array(
                    'name'=>'name',
                    'type'=>'raw',
                    'value'=>'CHtml::link($data->name, array("courses/index", "id"=>$data->id))',
                ),
	),
        'itemsCssClass'=>'table table-hover',
)); ?>
