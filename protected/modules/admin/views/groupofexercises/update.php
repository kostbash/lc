<div class="page-header clearfix">
    <?php echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>К курсу', array('/admin/courses/update', 'id'=>$exerciseGroup->id_course), array('class'=>'btn btn-success btn-icon', 'style'=>'float:left; width: 12%;')); ?>
    <h2 style="width: 61%; margin: 0 1%;">Редактирование блока: "<?php echo $exerciseGroup->name; ?>"</h2>
    <?php echo CHtml::link('Посмотреть<i class="glyphicon glyphicon-arrow-right"></i>', array('view', 'id'=>$exerciseGroup->id), array('class'=>'btn btn-primary btn-icon-right', 'target'=>'_blank', 'style'=>'float:left; width: 12%;')); ?>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#lessons-form').submit(); return false;", 'style'=>'float:right; width: 12%;')); ?>
</div>
<?php $this->renderPartial('_form', array('exerciseGroup'=>$exerciseGroup, 'skills'=>$skills)); ?>