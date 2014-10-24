<?php 
    $this->renderPartial("//lessons/_pass_scripts");
?>
<script type="text/javascript">
    function setDuration(){};
    $(function(){
        $('#exercises-form').submit(function(){
           return false; 
        });
    });
</script>
<div id="container">
    <div class="pass-lesson" id="lesson-page">
        <div class="page-header clearfix">
            <?php echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>Назад', array('update', 'id'=>$block->id), array('class'=>'btn btn-success btn-icon', 'style'=>'float:left')); ?>
            <h2 style="float: left; margin-left: 15px;"><?php echo "Блок : $block->name"; ?></h2>
        </div>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'exercises-form',
                'enableAjaxValidation'=>false,
            )); ?>
                <input type="hidden" name="Exercises" />
                <?php
                    $this->renderPartial("//lessons/blocks/{$block->type}", array(
                        'exerciseGroup'=>$block,
                        'exercisesTest' => $exercisesTest,
                    ));
                ?>
            <?php $this->endWidget(); ?>
    </div>
</div>