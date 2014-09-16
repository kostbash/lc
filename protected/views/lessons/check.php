<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js"); ?>
<script type="text/javascript">
    $(function(){
        $('.next-button').click(function(){
            result = true;
            $('input[name*=answers][type=text], input[name*=answers][type=hidden], select[name*=answers]').each(function(n, answer){
                if(!checkInput(answer))
                    result = false;
            });

            $('.checkboxes , .radio-buttons').each(function(n, answer){
                if(!checkRadioCheckBox(answer))
                    result = false;
            });
            $('.text-with-space').each(function(n, withSpace){
                if(!checkTextWithSpaces(withSpace))
                    result = false;
            });
            
            if(result) {
                $('#exercises-form').submit();
            } else {
                alert('Не для всех заданий даны ответы');
            }
            return false;
        });

        $('.block').click(function(){
            answer = $(this);
            key = answer.data('key');
            $('.block[data-key='+key+']').removeClass('selected');
            answer.addClass('selected');
            hidden = $('.hidden-answer[data-key='+key+']');
            hidden.val(answer.data('val'));
            checkInput(hidden);
        });

        $('.comparisons .list-one').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comparison'
        });

        $('.comparisons .list-two').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comparison'
        });

        $('.orderings').sortable({
            cancel: null,
            cursor: 'move',
            items: '> .word',
            tolerance: 'pointer',
            containment: 'parent'
        });
        
        $('.text-with-space .word').draggable({cursor: 'move', revert:true});
        $('.text-with-space .answer-droppable').droppable({
            accept: function(item){
                item_exericse_id = $(item).closest('.exercise').attr('id');
                drop_exericse_id = $(this).closest('.exercise').attr('id');
                return item_exericse_id === drop_exericse_id;
            },
            tolerance:'pointer',
            drop: function(event,info)
            {
                answer = $(info.draggable);
                cont = $(this);
                words = cont.closest('.text').siblings('.words');
                existWord = cont.find('.word');
                if(existWord.length)
                {
                    words.append(existWord);
                }
                cont.append(answer);
            }
        });
            
        $('[name*=answers]').keydown(function(e){
            nextTab = null;
            current = $(this);
            if(e.keyCode==13){
              $('[tabindex]').each(function(n, tabElement){
                  tab = $(tabElement);
                  if( parseInt(tab.attr('tabindex')) > current.attr('tabindex') )
                  {
                      if(!nextTab)
                          nextTab = tab;
                  }   
              });
              if(nextTab)
                  nextTab.focus();
              return false;
            }
        });
        $('#skills').popover();
        
        $('input[name*=answers][type=text], select[name*=answers]').change(function(){
            checkInput(this);
        });

        $('.checkboxes , .radio-buttons').change(function(){
            checkRadioCheckBox(this);
        });
    });
    
    function checkInput(input)
    {
        input = $(input);
        if( !$.trim(input.val()) )
        {
            input.closest('.answer').addClass('no-selected');
            return false;
        } else {
            input.closest('.answer').removeClass('no-selected');
            return true;
        }
    }
    
    function checkTextWithSpaces(withSpaces)
    {
        withSpaces = $(withSpaces);
        drops = withSpaces.find('.text .answer-droppable');
        res = true;
        drops.each(function(n, space) {
            space = $(space);
            if(!space.find('.word').length)
            {
                withSpaces.closest('.answer').addClass('no-selected');
                res = false;
                return false;
            }
        });
        if(res)
            withSpaces.closest('.answer').removeClass('no-selected');
        return res;
    }
    
    function checkRadioCheckBox(answer)
    {
        answer = $(answer);
        checked = answer.find('input:checked');
        if( !checked.length )
        {
            answer.closest('.answer').addClass('no-selected');
            return false;
        } else {
            answer.closest('.answer').removeClass('no-selected');
            return true;
        }
    }
</script>

    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Проверьте насколько уверенно ваш ребенок складывает и считает</div>
                    <div class="foot">Проверка насколько ваш ребенок уверенно складывает и считает позволяет заранее узнать о его умственных способностяхи подготовиться к разного рода сюрпризам.</div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="check-page">
        <div id="lesson-page">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'exercises-form',
                'enableAjaxValidation'=>false,
            )); ?>
            <h2 class="block-name">ЗАПИШИТЕ ОТВЕТ В ПОЛЯ СПРАВА ОТ ВОПРОСОВ</h2>
            <?php if($exercises) : $position=0; ?>
                <div id="exercises">
                    <?php foreach($exercises as $index => $exercise) : $position++; ?>
                        <div id="exercise_<?php echo $index; ?>" class="exercise <?php echo (++$i%2)==0 ? 'even' : 'odd'; ?>">
                            <div class="head clearfix">
                                <div class="number"><?php echo ++$number; ?></div>
                                <div class="condition"><?php echo "$exercise->condition"; ?></div>
                            </div>
                            <div class="answer clearfix">
                                <?php if($exercise->id_visual) : ?>
                                    <?php $this->renderPartial("/exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$exercise->id, 'index'=>$index+1)); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                Нет заданий
                <input type="hidden" name="Exercises" />
            <?php endif; ?>

            <div id="bottom">
                <?php if($nextGroup) : ?>
                    <?php echo CHtml::link('Далее', '#', array('class'=>'next-button', 'tabindex'=>$key+2));?>
                    <div id="leftStep"><?php echo "Вам осталось <b>$leftStep</b> шага"; ?></div>
                <?php else : ?>
                    <?php echo CHtml::link('Просмотреть результат', '#', array('class'=>'next-button', 'tabindex'=>$key+2));?>
                <?php endif; ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
