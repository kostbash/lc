<script>
    $(function(){
        $('#new-needknows').click(function(){
            current = this;
            field = $('#new-needknows-field');
            value = field.val();
            if(value)
            {
                tbody = $('#needknows-grid tbody');
                tbody.append('<tr><td style="width: 90%"><input class="form-control" placeholder="Введите название" type="text" value="'+value+'" name="Courses[Needknows][]"></td><td style="width: 10%"><a class="delete-needknows" title="Удалить" href="#"><img src="/images/grid-delete.png" alt="Удалить"></a></td></tr>');
                field.val('');
            }
            else
            {
                alert('Введите название, что нужно знать');
            }
            return false;
        });
        
        $('#new-yougets').click(function(){
            current = this;
            field = $('#new-yougets-field');
            value = field.val();
            if(value)
            {
                tbody = $('#yougets-grid tbody');
                tbody.append('<tr><td style="width: 90%"><input class="form-control" placeholder="Введите название" type="text" value="'+value+'" name="Courses[Yougets][]"></td><td style="width: 10%"><a class="delete-yougets" title="Удалить" href="#"><img src="/images/grid-delete.png" alt="Удалить"></a></td></tr>');
                field.val('');
            }
            else
            {
                alert('Введите название, что получите');
            }
            return false;
        });
        
        $('.delete-needknows, .delete-yougets').live('click', function(){
            current = $(this);
            current.closest('tr').remove();
            return false;
        });
    });
</script>

<div class="page-header clearfix">
    <h2>Создание курса</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Создать', array('courses/create'), array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#courses-form').submit(); return false;")); ?>
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
                    <?php echo CHtml::dropDownList('Courses[Subjects]', '', CourseSubjects::listData(), array('class'=>'form-control', 'placeholder'=>'Выберите предмет', 'size'=>2, 'multiple'=>'multiple')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 col-md-2"><label>Классы</label></div>
                <div class="col-lg-6 col-md-6">
                    <?php echo CHtml::dropDownList('Courses[Classes]', '', CourseClasses::listData(), array('class'=>'form-control', 'placeholder'=>'Выберите класс', 'size'=>2, 'multiple'=>'multiple')); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2"><label>Что нужно знать</label></div>
                <div class="col-lg-6 col-md-6">
                    <table class="table table-hover zgrid" id="needknows-grid">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                    <table class="table table-hover zgrid" id="yougets-grid">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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