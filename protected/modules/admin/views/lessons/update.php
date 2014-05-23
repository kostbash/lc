<div class="page-header clearfix">
    <h2>Редактирование урока: "<?php echo $model->name; ?>"</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#lessons-form').submit(); return false;")); ?>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'skills'=>$skills)); ?>