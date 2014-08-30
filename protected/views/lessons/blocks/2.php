<script type="text/javascript">
    $(function(){
        $('#exercises-form input[type=submit]').click(function(){
            result = true;
            $('input[name*=answers][type=text], input[name*=answers][type=hidden], select[name*=answers]').each(function(n, answer){
                if(!checkInput(answer))
                    result = false;
            });

            $('.checkbox-answer , .radio-answer').each(function(n, answer){
                if(!checkRadioCheckBox(answer))
                    result = false;
            });
            $('.text-with-space').each(function(n, withSpace){
                if(!checkTextWithSpaces(withSpace))
                    result = false;
            });
            if(!result)
                alert('Не для всех заданий даны ответы');
            return result;
        });

        $('.for-editor-field').click(function(){
            answer = $(this);
            setDuration(answer);
            key = answer.data('key');
            $('.for-editor-field[data-key='+key+']').removeClass('selected-answer');
            answer.addClass('selected-answer');
            hidden = $('.hidden-answer[data-key='+key+']');
            hidden.val(answer.data('val'));
            checkInput(hidden);
        });

        $('.comparisons .list-one').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comparison',
            update: function(event, ui) {
                setDuration(this);
            }
        });

        $('.comparisons .list-two').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comparison',
            update: function(event, ui) {
                setDuration(this);
            }
        });

        $('.orderings ul').sortable({
            cancel: null,
            cursor: 'move',
            items: '> .word',
            containment: 'parent',
            update: function(event, ui) {
                setDuration(this);
            }
        });
        $('.text-with-space .word').draggable({cursor: 'move', revert:true});
        $('.text-with-space .answer-droppable').droppable({
            accept:'.text-with-space .word',
            tolerance:'pointer',
            drop: function(event,info)
            {
                answer = $(info.draggable);
                cont = $(this);
                setDuration(cont);
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
            setDuration(this);
            checkInput(this);
        });

        $('.checkbox-answer , .radio-answer').change(function(){
            setDuration(this);
            checkRadioCheckBox(this);
        });
    });
    
    function checkInput(input)
    {
        input = $(input);
        if( !$.trim(input.val()) )
        {
            input.closest('.answer').addClass('no-selected-answer');
            return false;
        } else {
            input.closest('.answer').removeClass('no-selected-answer');
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
                withSpaces.closest('.answer').addClass('no-selected-answer');
                res = false;
                return false;
            }
        });
        if(res)
            withSpaces.closest('.answer').removeClass('no-selected-answer');
        return res;
    }
    
    function checkRadioCheckBox(answer)
    {
        answer = $(answer);
        checked = answer.find('input:checked');
        if( !checked.length )
        {
            answer.closest('.answer').addClass('no-selected-answer');
            return false;
        } else {
            answer.closest('.answer').removeClass('no-selected-answer');
            return true;
        }
    }
</script>


<?php if($exercisesTest) : $position=0; ?>
    <?php foreach($exercisesTest as $key => $exercise) : $classExercise = (++$i%2)==0 ? ' gray' : ''; $position++; ?>
        <div class="exercise clearfix<?php echo $classExercise; ?>">
            <h2><?php echo "Задание $position:"; ?></h2>
            <div class="question"><?php echo "$exercise->condition"; ?></div>
            <div class="answer clearfix">
                <?php if($exercise->id_visual) $this->renderPartial("//exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$key, 'index'=>$key+1)); ?>
            </div>
            <input class="duration" type="hidden" name="Exercises[<?php echo $key; ?>][duration]" value="0" />
        </div>
    <?php endforeach; ?>
    <div class='control-buttons'><?php echo CHtml::submitButton('Отправить результаты', array('class'=>'btn btn-success', 'style'=>'margin-top: 20px', 'tabindex'=>$key+2)); ?></div>
<?php else : ?>
    Нет заданий
<?php endif; ?>
