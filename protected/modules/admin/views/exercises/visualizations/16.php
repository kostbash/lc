<script>
    $(function(){
        $('#exercises-form').submit(function(){
            $return = true;    
            return $return;
        });
    });
</script>

<div id="universal">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <?php echo CHtml::textArea("Exercises[questions][0][text]", $model->Questions[0]->text, array('class'=>'form-control question-text', 'rows'=>'3', 'placeholder'=>'Введите текст')); ?>
            <div class="errorMessage"></div>
        </div>
    </div>
</div>
