<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js"); ?>
<script type="text/javascript">
    function setDuration(){};
    $(function(){
        $('#exercises-form').submit(function(){
           return false; 
        });
    });
</script>
<div class="pass-lesson">
    <div class="page-header clearfix">
        <?php echo CHtml::link('<i class="glyphicon glyphicon-arrow-left"></i>Назад', array('update', 'id'=>$block->id), array('class'=>'btn btn-success btn-icon', 'style'=>'float:left')); ?>
        <h2 style="float: left; margin-left: 15px;"><?php echo "Блок : $block->name"; ?></h2>
    </div>
    <div class="row" style="position: relative">
        <div class="exercises">
            <div class="widget">
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
    </div>
</div>