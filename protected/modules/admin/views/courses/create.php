<div class="page-header clearfix">
    <h2>Создание курса</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Создать', array('courses/create'), array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#courses-form').submit(); return false;")); ?>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>