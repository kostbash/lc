<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<script>
    $(function(){
        $('.search-form form').change(function(){
            $('#users-grid').yiiGridView('update', { data: $(this).serialize() });
        });
    });
</script>

<div class="well search-form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->label($model,'role'); ?>
            <?php $roles = Users::$rolesRusNames; unset($roles[1]); // удаляем админа роль=1 ?>
            <?php echo $form->dropDownList($model,'role', $roles, array('class'=>'form-control input-sm', 'empty'=>'Все')); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->label($model,'registration_day'); ?>
            <?php echo $form->dropDownList($model,'registration_day', Users::listRegistrationDates(), array('class'=>'form-control input-sm', 'empty'=>'Все')); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->label($model,'last_activity'); ?>
            <?php echo $form->dropDownList($model,'last_activity', Users::listLastActivity(), array('class'=>'form-control input-sm', 'empty'=>'Все')); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->label($model,'last_unactivity'); ?>
            <?php echo $form->dropDownList($model,'last_unactivity', Users::listLastUnActivity(), array('class'=>'form-control input-sm', 'empty'=>'Все')); ?>
        </div>
    </div>
    <div class="row row-attr">
        <div class="col-md-3">
            <?php echo $form->label($model,'countLessons'); ?>
            <?php echo $form->dropDownList($model,'countLessons', Users::$listCountLessons, array('class'=>'form-control input-sm', 'empty'=>'Не выбрано')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->