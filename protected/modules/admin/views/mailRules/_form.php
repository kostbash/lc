<script>
    $(function(){
        
        $('.passed_course .search').keyup(function(e){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courses/coursesByAjax'); ?>',
                type:'POST',
                data: { term: current.val() },
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                    {
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result.html);
                        current.siblings('.input-group-btn').addClass('open');
                    }
                }
            });
            
            if(current.val()==='')
            {
                $('#MailRules_passed_course').val('');
            }
            return false;
        });

        $('.passed_course .dropdown-toggle').click(function(e){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courses/coursesByAjax'); ?>',
                type:'POST',
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                    {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result.html);
                    }
                }
            });
        });

        $('.passed_course .dropdown-menu li').live('click', function(){
            current = $(this);
            id = current.data('id');
            if(id)
            {
                current.closest('.mydrop').find('.search').val(current.find('a').html());
                $('#MailRules_passed_course').val(id);
            }
            current.closest('.input-group-btn').removeClass('open');
            return false;
        });
    });
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mail-rules-form',
	'enableAjaxValidation'=>false,
)); ?>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'name'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'name',array('maxlength'=>100, 'class'=>'form-control', 'placeholder'=>'Введите название')); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'use_number'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'use_number',array('maxlength'=>3, 'class'=>'form-control', 'placeholder'=>'Введите число применений')); ?>
            <?php echo $form->error($model,'use_number'); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'interval'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'interval', array('maxlength'=>6, 'class'=>'form-control', 'placeholder'=>'Введите интервал')); ?>
            <?php echo $form->error($model,'interval'); ?>
        </div>
        <div class="col-md-2">
            <div style="line-height: 34px">дней</div>
        </div>
    </div>
    
    <h2 style='margin-bottom: 26px'>Фильтры</h2>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'roles'); ?>
        </div>
        <div class="col-md-4">
            <?php echo CHtml::checkBoxList('MailRules[roles]', unserialize($model->roles), array(2=>'Ученик', 4=>'Родитель')); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'passed_reg_days'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'passed_reg_days', array('maxlength'=>6, 'class'=>'form-control', 'placeholder'=>'Введите число дней прошедшее с момента регистрации')); ?>
            <?php echo $form->error($model,'passed_reg_days'); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'unactivity_days'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'unactivity_days', array('maxlength'=>6, 'class'=>'form-control', 'placeholder'=>'Введите число дней не активности ученика')); ?>
            <?php echo $form->error($model,'unactivity_days'); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'unpassed_check_test'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'unpassed_check_test', array('maxlength'=>6, 'class'=>'form-control', 'placeholder'=>'Введите число дней прошедшее с момента прохождения')); ?>
            <?php echo $form->error($model,'unpassed_check_test'); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'passed_course'); ?>
            <?php echo $form->hiddenField($model,'passed_course'); ?>
        </div>
        <div class="col-md-4 passed_course">
            <div class="input-group mydrop">
                <?php echo CHtml::textField("", $model->PassedCourse->name, array('class'=>'form-control search', 'placeholder'=>'Введите название пройденного курса', 'autocomplete'=>'off')); ?>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                    </ul>
                </div>
            </div>
            <?php echo $form->error($model,'passed_course'); ?>
        </div>
        
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'number_of_passed_courses'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'number_of_passed_courses', array('maxlength'=>6, 'class'=>'form-control', 'placeholder'=>'Введите число пройденных курсов')); ?>
            <?php echo $form->error($model,'number_of_passed_courses'); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->labelEx($model,'number_of_passed_lessons'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textField($model,'number_of_passed_lessons', array('maxlength'=>6, 'class'=>'form-control', 'placeholder'=>'Введите число пройденных уроков')); ?>
            <?php echo $form->error($model,'number_of_passed_lessons'); ?>
        </div>
    </div>
<?php $this->endWidget(); ?>

</div><!-- form -->