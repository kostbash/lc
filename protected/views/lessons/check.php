<script type="text/javascript">
    $(function(){
        $('.exercise .answer input').focusout(function(){
            if($(this).val()) {
                $(this).closest('.answer').removeClass('has-error has-feedback');
                $(this).siblings('.form-control-feedback').remove();
            }
            else {
                $(this).siblings('.form-control-feedback').remove();
                $(this).closest('.answer').addClass('has-error has-feedback').append('<span class="glyphicon glyphicon-pencil form-control-feedback"></span>');
            }
        });
        $('#check input[type=submit]').click(function(){
            can = true;
            $('.exercise .answer input').each(function(){
               if(!$.trim($(this).val()))
                {
                    can = false;
                    $(this).closest('.answer').append('<span class="glyphicon glyphicon-pencil form-control-feedback"></span>').addClass('has-error has-feedback');
                }
            });
            if(!can) {
                alert('Не для всех вопросов даны ответы');
                return false;
            }
        });
        $('.exercise .answer input').keydown(function(e){
              if(e.keyCode==13){
                nextTab = $('input[tabindex='+(parseInt($(this).attr('tabindex'))+1)+']');
                nextTab.focus();
                return false;
              }
         });
    });
</script>
<div id="check">
<?php $form=$this->beginWidget('CActiveForm', array(
	'method'=>'POST',
)); ?>
<div class="row">
    <div class="head col-lg-8 col-md-8">
        <h1>Проверьте насколько уверенно Ваш ребенок складывает и считает</h1>
        <div class="fordummy">Запишите ответ в поля справа от вопросов</div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="reg"><?php echo CHtml::link("Начать обучение", array('site/index', 'showreg'=>true), array('class'=>'btn btn-success')); ?></div>
        <div class="login"><p>или <?php echo CHtml::link("Войдите", array('site/index', 'showlogin'=>true)); ?> если Вы уже зарегистрированы</p></div>
    </div>
</div>
    
<div id="exercises">
    <?php foreach($exercises as $index => $exercise) : ?>
        <div class="exercise clearfix">
            <h2>Вопрос <?php echo ++$number; ?></h2>
            <div class="row">
                <div class="question col-lg-3 col-md-3">
                    <?php echo $exercise->condition; ?>
                </div>
                <div class="answer col-lg-2 col-md-2 clearfix">
                    <?php echo CHtml::textField("Exercises[$exercise->id][answer]", '', array('class'=>'form-control', 'placeholder'=>'Введите ответ', 'tabindex'=>$index, 'autocomplete'=>'off')); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div id="bottom">
    <?php if($nextGroup) : ?>
        <?php echo CHtml::submitButton('Далее', array('class'=>'btn btn-primary'));?>
        <div id="leftStep"><?php echo "Вам осталось  шагов: <b>$leftStep</b>"; ?></div>
    <?php else : ?>
        <?php echo CHtml::submitButton('Просмотреть результат', array('class'=>'btn btn-primary'));?>
    <?php endif; ?>
</div>
<?php $this->endWidget(); ?>
</div>
