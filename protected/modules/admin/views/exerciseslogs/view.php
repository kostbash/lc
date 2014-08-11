<div class="section">
    <h3 class='head'>Ответ ученика на задание</h3>
    <div class="pass-lesson">
        <div class="exercise clearfix">
            <div class="question"><b>Условие задания:</b> <?php echo "$exercise->condition"; ?></div>
            <div class="answer clearfix">
                <?php if($exercise->id_visual) $this->renderPartial("visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'answers'=>$model->answerUnserialize)); ?>
            </div>

            <div class="result color-<?php if(!$model->right) echo 'un'; ?>right"><?php echo $model->right ? "Верно" : "Не верно"; ?></div>
        </div>
    </div>
</div>