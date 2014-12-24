<div class="page-header clearfix">
    <h2>Редактирование задания</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon','onclick'=>"$('#exercises-form').submit(); return false;")); ?>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'id_group'=>$id_group, 'id_part'=>$id_part, 'id_map'=>$id_map, 'groupExercise'=>$groupExercise)); ?>