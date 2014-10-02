<script type="text/javascript">
    $(function(){
        $('[name*=answers]').change(function(){
            current = $(this);
            resultAnswer = current.closest('.exercise').find('> .head .result');
            if(current.val()=='')
                resultAnswer.html('');
            else
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: current.closest('.answer').find('input,select').serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        else if(result==0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                        else
                            alert(result);
                     }
                });
        });

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
            setDuration(answer);
            key = answer.data('key');
            $('.block[data-key='+key+']').removeClass('selected');
            answer.addClass('selected');
            hiddenAnswer = $('.hidden-answer[data-key='+key+']')
            hiddenAnswer.val(answer.data('val'));

            resultAnswer = answer.closest('.exercise').find('> .head .result');
            $.ajax({
                url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                type:'POST',
                data: hiddenAnswer.serialize(),
                success: function(result) { 
                    if(result==1)
                        resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                    else if(result==0)
                        resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
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
                resultAnswer = current.closest('.exercise').find('> .head .result');
                hiddenAnswer = current.closest('.answer').find('.hidden-answer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        else if(result==0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
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
                resultAnswer = current.closest('.exercise').find('> .head .result');
                hiddenAnswer = current.closest('.answer').find('.hidden-answer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        else if(result==0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                    }
                });
            }
        });

        $('.orderings').sortable({
            cancel: null,
            cursor: 'move',
            items: '> .word',
            tolerance: 'pointer',
            containment: 'parent',
            update: function(event, ui) {
                current = $(this);
                setDuration(current);
                resultAnswer = current.closest('.exercise').find('> .head .result');
                hiddenAnswer = current.closest('.answer').find('.hidden-answer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type:'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) { 
                        if(result==1)
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        else if(result==0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                    }
                });
            }
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
                setDuration(cont);
                words = cont.closest('.text').siblings('.words');
                existWord = cont.find('.word');
                if(existWord.length)
                {
                    words.append(existWord);
                }
                cont.append(answer);
                resultAnswer = cont.closest('.exercise').find('> .head .result');
                hiddenAnswer = cont.closest('.text').find('.hidden-answer');
                if(!words.find('.word').length)
                {
                    $.ajax({
                        url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                        type:'POST',
                        data: hiddenAnswer.serialize(),
                        success: function(result) { 
                            if(result==1)
                                resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                            else if(result==0)
                                resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                            else
                                alert(result);
                        }
                    });
                } 
                else
                {
                    resultAnswer.html('');
                }
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

        $('.checkboxes , .radio-buttons').change(function(){
            setDuration(this);
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

<?php if($exerciseGroup->Exercises) : $posTest = 1; ?>
    <div id="exercises">
        <?php foreach($exerciseGroup->Exercises as $i => $exercise) : ?>
            <div id="exercise_<?php echo $i; ?>"  class="exercise <?php echo (++$i%2)==0 ? 'even' : 'odd'; ?>">
                <div class="head clearfix">
                    <div class="number"><?php echo $posTest++; ?></div>
                    <div class="condition"><?php echo "$exercise->condition"; ?></div>
                    <div class="result"></div>
                </div>
                <div class="answer clearfix">
                    <?php if($exercise->id_visual) : ?>
                        <?php $this->renderPartial("//exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$exercise->id, 'index'=>$i)); ?>
                    <?php endif; ?>
                </div>
                <input class="duration" type="hidden" name="Exercises[<?php echo $exercise->id; ?>][duration]" value="0" />
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    Нет заданий
<?php endif; ?>
<div class='control-buttons'>
    <?php
        if($userAndExerciseGroup)
        {
            echo $userAndExerciseGroup->getNextButton(++$i);
        }
    ?>
</div>
