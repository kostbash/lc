<div class="dropdown-list">
    <?php echo CHtml::dropDownList("Exercises[$key][answers]", '', CHtml::listData($model->Answers, 'id', 'answer'), array('empty'=>'Выберите правильный ответ', 'tabindex'=>$index)); ?>
</div>