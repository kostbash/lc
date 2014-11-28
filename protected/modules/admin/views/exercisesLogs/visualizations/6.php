<div class="comparisons clearfix">
    <div class="list-one">
        <?php foreach($answers[1] as $id_answer_one) : ?>
            <div class='comparison'>
                <div class='comp-answer'><?php echo ExercisesListOfAnswers::model()->findByPk($id_answer_one)->answer; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="list-two">
        <?php foreach($answers[2] as $id_answer_two) : ?>
            <div class='comparison'>
                <div class='comp-answer'><?php echo ExercisesListOfAnswers::model()->findByPk($id_answer_two)->answer; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>