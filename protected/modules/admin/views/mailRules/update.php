<div class="page-header clearfix">
    <h2>Редактирование правила - "<?php echo $cloneModel->name; ?>"</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#mail-rules-form').submit(); return false;")); ?>
</div>
<?php $this->renderPartial('_form', array('model'=>$model));?>


<?php
    CVarDumper::dump($model->selectUsers(), 10, true);
?>