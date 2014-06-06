<div style="text-align: left; display: inline-block;" class="radio-answer">
    <?php echo CHtml::radioButtonList("Exercises[$key][answers]", '', CHtml::listData($model->Answers, 'id', 'answer'), array('tabindex'=>$index)); ?>
</div>