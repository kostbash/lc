<?php if(!empty($model->Answers)) : ?>
    <?php foreach($model->Answers as $answer) : ?>
        <div style="min-height: 34px;" class='for-editor-field<?php if($answer->id==$answers) echo ' selected-answer'; ?>'><?php echo $answer->answer; ?></div>
    <?php endforeach; ?>
<?php endif; ?>