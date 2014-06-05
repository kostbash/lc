<div style="text-align: left; display: inline-block;">
    <?php echo CHtml::checkBoxList("Exercises[$key][answers]", '', CHtml::listData($model->Answers, 'id', 'answer')); ?>
</div>