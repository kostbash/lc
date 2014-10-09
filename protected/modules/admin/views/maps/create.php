<div class="page-header clearfix">
    <h2>Добавление карты</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#maps-form').submit(); return false;")); ?>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>