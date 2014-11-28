<div style="text-align: left; display: inline-block;" class="checkbox-answer">
    <?php echo CHtml::checkBoxList("Exercises[answers]", $answers, CHtml::listData($model->Answers, 'id', 'answer')); ?>
</div>