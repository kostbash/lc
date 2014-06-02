<script type="text/javascript">
    $(function(){
        
    });
</script>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exercise-form',
	'enableAjaxValidation'=>false,
)); ?>
    
<div class="row">
    <div class="col-lg-4 col-md-4">
        <?php echo CHtml::label('Умения', ''); ?>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <div id="add-skills">
            <div class="input-group mydrop ">
                <?php echo CHtml::textField("Skills[name]", '', array('placeholder'=>'Введите название умения', 'class'=>'form-control input-sm', 'id'=>'searchSkill', 'autocomplete'=>'off')); ?>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                    </ul>
                </div>
            </div>
            <div class="skills"></div>
        </div>
    </div>
</div>
    
<div class="row">
    <div class="col-lg-4 col-md-4">
        <?php echo $form->label($model, 'difficulty'); ?>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <?php echo $form->dropDownList($model, 'difficulty', Exercises::getDataDifficulty(), array('class'=>'form-control', 'placeholder'=>'Введите условие задания')); ?>
        <?php echo $form->error($model, 'difficulty'); ?>
    </div>
</div>
    
<div class="row">
    <div class="col-lg-4 col-md-4">
        <?php echo $form->label($model, 'condition'); ?>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <?php echo $form->textField($model, 'condition', array('class'=>'form-control', 'placeholder'=>'Введите условие задания')); ?>
        <?php echo $form->error($model, 'condition'); ?>
    </div>
</div>
    
<?php 
if($model->id_visual)
    $this->renderPartial("visualizations/{$model->id_type}_{$model->id_visual}", array('model'=>$model));
?>
    
<?php $this->endWidget(); ?>
</div>