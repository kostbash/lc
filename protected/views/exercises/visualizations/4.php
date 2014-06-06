<div style="text-align: left; display: inline-block;" class="checkbox-answer">
    <?php echo CHtml::checkBoxList("Exercises[$key][answers]", '', CHtml::listData($model->Answers, 'id', 'answer'), array('tabindex'=>$index)); ?>
</div>