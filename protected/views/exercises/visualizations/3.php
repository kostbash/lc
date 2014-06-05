<div style="text-align: left; display: inline-block;">
    <?php echo CHtml::radioButtonList("Exercises[$key][answers]", '', CHtml::listData($model->Answers, 'id', 'answer')); ?>
</div>