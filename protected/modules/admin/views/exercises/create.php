<div class="page-header clearfix">
    <h2>Создание задания</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Создать', '#', array('class'=>'btn btn-success btn-icon','onclick'=>"$('#lessons-form').submit(); return false;")); ?>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>