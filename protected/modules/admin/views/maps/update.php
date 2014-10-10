<?php
    //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/mapImages.js");
    //Yii::app()->clientScript->registerCSSFile(Yii::app()->request->baseUrl . "/css/mapImages.css");
?>
<div class="page-header clearfix">
    <h2>Редактирование урока: "<?php echo $clone->name; ?>"</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#maps-form').submit(); return false;")); ?>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'clone'=>$clone)); ?>