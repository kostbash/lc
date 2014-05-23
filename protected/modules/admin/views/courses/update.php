<div class="page-header clearfix">
    <div class="row">
        <div class="col-lg-10 col-md-10">
             <h2>Редактирование курса: "<?php echo $model->name; ?>"</h2>   
        </div>
        <div class="col-lg-2 col-md-2">
            <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#courses-form').submit(); return false;")); ?>
        </div>        
    </div>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>