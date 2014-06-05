<?php if(!empty($model->Answers)) : ?>
    <?php foreach($model->Answers as $answer) : ?>
        <div data-key="<?php echo $key; ?>" data-val="<?php echo $answer->id; ?>" class='for-editor-field'><?php echo $answer->answer; ?></div>
    <?php endforeach; ?>
<?php endif; ?>
<?php echo CHtml::hiddenField("Exercises[$key][answers]", '', array('id'=>false, 'class'=>'hidden-answer', 'data-key'=>$key)); ?>