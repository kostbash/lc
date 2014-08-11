<script type="text/javascript">
    $(function(){
        $('[name*=answers]').change(function(){
            current = $(this);
            resultAnswer = current.closest('.answer').siblings('.result');
            if(current.val()=='')
                resultAnswer.html('');
            else
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: current.closest('.answer').find('input,select').serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('color-unright').addClass('color-right').html('Верно');
                        else if(result==0)
                            resultAnswer.removeClass('color-right').addClass('color-unright').html('Не верно');
                        else
                            alert(result);
                     }
                });
        });

        $('.nextGroup').click(function(){
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

            if(result) {
                $('#exercises-form').submit();
            } else {
                alert('Не для всех заданий даны ответы');
            }
            return false;
        });

        $('.for-editor-field').click(function(){
            answer = $(this);
            setDuration(answer);
            key = answer.data('key');
            $('.for-editor-field[data-key='+key+']').removeClass('selected-answer');
            answer.addClass('selected-answer');
            hiddenAnswer = $('.hidden-answer[data-key='+key+']')
            hiddenAnswer.val(answer.data('val'));

            resultAnswer = answer.closest('.answer').siblings('.result');
            $.ajax({
                url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                type:'POST',
                data: hiddenAnswer.serialize(),
                success: function(result) { 
                    if(result==1)
                        resultAnswer.removeClass('color-unright').addClass('color-right').html('Верно');
                    else if(result==0)
                        resultAnswer.removeClass('color-right').addClass('color-unright').html('Не верно');
                }
            });
            checkInput(hiddenAnswer);
        });

        $('.comparisons .list-one').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comparison',
            update: function(event, ui) {
                current = $(this);
                setDuration(current);
                resultAnswer = current.closest('.exercise').find('> .result');
                hiddenAnswer = current.closest('.answer').find('.hidden-answer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('color-unright').addClass('color-right').html('Верно');
                        else if(result==0)
                            resultAnswer.removeClass('color-right').addClass('color-unright').html('Не верно');
                    }
                });
            }
        });

        $('.comparisons .list-two').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comparison',
            update: function(event, ui) {
                current = $(this);
                setDuration(current);
                resultAnswer = current.closest('.exercise').find('> .result');
                hiddenAnswer = current.closest('.answer').find('.hidden-answer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('color-unright').addClass('color-right').html('Верно');
                        else if(result==0)
                            resultAnswer.removeClass('color-right').addClass('color-unright').html('Не верно');
                    }
                });
            }
        });

        $('.orderings ul').sortable({
            cancel: null,
            cursor: 'move',
            items: '> .word',
            containment: 'parent',
            update: function(event, ui) {
                current = $(this);
                setDuration(current);
                resultAnswer = current.closest('.exercise').find('> .result');
                hiddenAnswer = current.closest('.answer').find('.hidden-answer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('color-unright').addClass('color-right').html('Верно');
                        else if(result==0)
                            resultAnswer.removeClass('color-right').addClass('color-unright').html('Не верно');
                    }
                });
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
                resultAnswer = cont.closest('.exercise').find('> .result');
                hiddenAnswer = cont.closest('.text').find('.hidden-answer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('color-unright').addClass('color-right').html('Верно');
                        else if(result==0)
                            resultAnswer.removeClass('color-right').addClass('color-unright').html('Не верно');
                        else
                            alert(result);
                    }
                });
            }
        });
        $('[name*=answers]').keydown(function(e){
            nextTab = null;
            current = $(this);
            if(e.keyCode==13){
              $('[tabindex]').each(function(n, tabElement){
                  tab = $(tabElement);
                  //alert(parseInt(tab.attr('tabindex')) +' - '+ parseInt(current.attr('tabindex')));
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

<?php if($exerciseGroup->Exercises) : $posTest = 1; ?>
    <?php foreach($exerciseGroup->Exercises as $i => $exercise) : $classExercise = (++$i%2)==0 ? ' gray' : ''; ?>
        <div class="exercise clearfix<?php echo $classExercise; ?>">
            <h2><?php if($exercise->id_type!==4) echo "Задание $posTest:"; else echo 'Теория:'; ?></h2>
            <?php if($exercise->id_type!==4) : ++$posTest; ?>
                <div class="question"><?php echo "$exercise->condition"; ?></div>
                <div class="answer clearfix">
                    <?php if($exercise->id_visual) $this->renderPartial("/exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$exercise->id, 'index'=>$i)); ?>
                </div>
                <div class="result"></div>
            <?php else : ?>
                <?php echo $exercise->condition; ?>
            <?php endif; ?>
            <input class="duration" type="hidden" name="Exercises[<?php echo $exercise->id; ?>][duration]" value="0" />
        </div>
    <?php endforeach; ?>
<?php else : ?>
    Нет заданий
<?php endif; ?>
<div class='control-buttons'>
    <?php
        echo $userAndExerciseGroup->getNextButton(++$i);
    ?>
</div>
