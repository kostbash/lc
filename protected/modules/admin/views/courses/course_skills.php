<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js");
?>

<div class="page-header clearfix">
    <div class="row">
        <div class="col-lg-7 col-md-7">
            <h2>Редактирование курса: "<?php echo $model->name; ?>"</h2>
        </div>
        <div class="col-lg-5 col-md-5"><?php /* ?>
            <div class="export-button">
                <button type="button" class="dropdown-toggle" data-toggle="dropdown">Экспорт курса<span class="caret"></span></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="<?php echo Yii::app()->createUrl('courses/print', array('id'=>$model->id, 'with_right'=>0)); ?>" target="_blank">Печать</a></li>
                    <li><a href="<?php echo Yii::app()->createUrl('courses/toPdf', array('id'=>$model->id, 'with_right'=>0)); ?>" target="_blank">PDF</a></li>
                </ul>
                <input id='with-right' type='checkbox' class='with-right' name value='0' />
                <label for='with-right'>С ответами</label>
            </div>
            <?php */ echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#courses-form').submit(); return false;")); ?>
        </div>
    </div>
</div>

<div class="form" id="course" data-id="<?php echo $model->id; ?>">

    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'courses-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <div class="section main-attrs">
        <h3 class="head">Основное</h3>
        <div class="row">
            <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'name'); ?></div>
            <div class="col-lg-6 col-md-6">
                <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите название курса')); ?>
                <?php echo $form->error($model,'name'); ?>
            </div>
            <div class="col-lg-4 col-md-4">
                <?php echo CHtml::link('Умения курса', array('/admin/skills/index', 'id_course'=>$model->id), array('class'=>'btn btn-success btn-sm')); ?>
                <?php echo CHtml::link('<i class="glyphicon glyphicon-cog"></i>Параметры курса', array('/admin/courses/params', 'id_course'=>$model->id), array('class'=>'btn btn-primary btn-sm btn-icon')); ?>
                <?php echo CHtml::link('Код курса', array('/admin/courses/showcode', 'id_course'=>$model->id), array('class'=>'btn btn-success btn-sm')); ?>
            </div>
        </div>
    </div>

    <?php echo $form->textArea($model,'code',array('rows'=>50,'class'=>'form-control', 'placeholder'=>'')); ?>
    <?php echo $form->error($model,'code'); ?>
    <?php $this->endWidget(); ?>


