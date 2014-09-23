<script>
    $(function(){
        $('#new-needknows').click(function(){
            current = this;
            field = $('#new-needknows-field');
            if(field.val())
            {
                $.ajax({
                    url:'<?php echo Yii::app()->createUrl("admin/courseNeedknows/create", array('id_course'=>$model->id)); ?>',
                    type: 'POST',
                    data: { CourseNeedknows:{ name: field.val() } },
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                            $('#needknows-grid').yiiGridView('update');
                        else
                            alert(result.errors);
                    }
                });
            }
            else
            {
                alert('Введите название, что нужно знать');
            }
            return false;
        });

        $('#needknows-grid .update-record').live('change', function(){
            current = this;
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseNeedknows/update"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            });
        });
        
        $('#new-yougets').click(function(){
            current = this;
            field = $('#new-yougets-field');
            if(field.val())
            {
                $.ajax({
                    url:'<?php echo Yii::app()->createUrl("admin/courseYougets/create", array('id_course'=>$model->id)); ?>',
                    type: 'POST',
                    data: { CourseYougets:{ name: field.val() } },
                    dataType: 'json',
                    success: function(result) { 
                        if(result.success)
                            $('#yougets-grid').yiiGridView('update');
                        else
                            alert(result.errors);
                    }
                });
            }
            else
            {
                alert('Введите название, что нужно знать');
            }
            return false;
        });

        $('#yougets-grid .update-record').live('change', function(){
            current = this;
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseYougets/update"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            });
        });
    });
</script>
<div class="page-header clearfix">
    <div class="row">
        <div class="col-lg-8 col-md-8">
             <h2>Параметры курса "<?php echo $model->name; ?>"</h2>   
        </div>
        <div class="col-lg-4 col-md-4" style="text-align: right;">
            <?php echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>К курсу', array('/admin/courses/update', 'id'=>$model->id), array('class'=>'btn btn-primary btn-icon', 'style'=>'float:none;')); ?>
            <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'style'=>'float:none;', 'onclick'=>"$('#courses-form').submit(); return false;")); ?>
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
    <div class="row">
        <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'name'); ?></div>
        <div class="col-lg-6 col-md-6">
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите название курса')); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'learning_time'); ?></div>
        <div class="col-lg-6 col-md-6">
            <?php echo $form->textField($model,'learning_time',array('size'=>60, 'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите время обучения')); ?>
            <?php echo $form->error($model,'learning_time'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'difficulty'); ?></div>
        <div class="col-lg-6 col-md-6">
            <?php echo CHtml::dropDownList("Courses[difficulty]", $model->difficulty, Courses::$difficulties, array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'difficulty'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-md-2"><label>Предметы</label></div>
        <div class="col-lg-6 col-md-6">
            <?php echo CHtml::dropDownList('Courses[Subjects]', $model->idsSubjects, CourseSubjects::listData(), array('class'=>'form-control', 'placeholder'=>'Выберите предмет', 'size'=>2, 'multiple'=>'multiple')); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-md-2"><label>Классы</label></div>
        <div class="col-lg-6 col-md-6">
            <?php echo CHtml::dropDownList('Courses[Classes]', $model->idsClasses, CourseClasses::listData(), array('class'=>'form-control', 'placeholder'=>'Выберите класс', 'size'=>2, 'multiple'=>'multiple')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2"><label>Что нужно знать</label></div>
        <div class="col-lg-6 col-md-6">
            <?php $this->widget('ZGridView', array(
                    'id'=>'needknows-grid',
                    'dataProvider'=> new CArrayDataProvider($model->NeedKnows),
                    'columns'=>array(
                        array(
                            'header'=>'Название',
                            'name'=>'name',
                            'type'=>'textField',
                            'htmlOptions'=>array('style'=>'width: 90%'),
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{delete}',
                            'buttons'=>array(
                                'delete'=>array(
                                    'url' => 'Yii::app()->createUrl("admin/courseNeedknows/delete", array("id"=>$data->id))',
                                    'options'=>array('class'=>'delete'),
                                ),
                            ),

                            'htmlOptions'=>array('style'=>'width: 10%'),
                        ),
                    ),
            ));?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-offset-2 col-md-offset-2 col-lg-4 col-md-4">
            <input id="new-needknows-field" class="form-control" type="text" value="" placeholder="Введите новую строку" />
        </div>
        <div class="col-lg-2 col-md-2" style="text-align: right;">
            <a id="new-needknows" class="btn btn-success" href="#">Добавить строку</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2"><label>Что получите</label></div>
        <div class="col-lg-6 col-md-6">
            <?php $this->widget('ZGridView', array(
                    'id'=>'yougets-grid',
                    'dataProvider'=> new CArrayDataProvider($model->YouGets),
                    'columns'=>array(
                        array(
                            'header'=>'Название',
                            'name'=>'name',
                            'type'=>'textField',
                            'htmlOptions'=>array('style'=>'width: 90%'),
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{delete}',
                            'buttons'=>array(
                                'delete'=>array(
                                    'url' => 'Yii::app()->createUrl("admin/courseYougets/delete", array("id"=>$data->id))',
                                    'options'=>array('class'=>'delete'),
                                ),
                            ),

                            'htmlOptions'=>array('style'=>'width: 10%'),
                        ),
                    ),
            ));?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-offset-2 col-md-offset-2 col-lg-4 col-md-4">
            <input id="new-yougets-field" class="form-control" type="text" value="" placeholder="Введите новую строку" />
        </div>
        <div class="col-lg-2 col-md-2" style="text-align: right;">
            <a id="new-yougets" class="btn btn-success" href="#">Добавить строку</a>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->