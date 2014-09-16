<script type="text/javascript">
    $(function(){
        $('.send-result-button').click(function(){
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

        $('.orderings').sortable({
            cancel: null,
            cursor: 'move',
            items: '> .word',
            tolerance: 'pointer',
            containment: 'parent',
            update: function(event, ui) {
                setDuration(this);
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

<?php if($exercisesTest) : $position=0; ?>
    <div id="exercises">
        <?php foreach($exercisesTest as $key => $exercise) : $position++; ?>
            <div id="exercise_<?php echo $key; ?>" class="exercise <?php echo (++$i%2)==0 ? 'even' : 'odd'; ?>">
                <div class="head clearfix">
                    <div class="number"><?php echo $position; ?></div>
                    <div class="condition"><?php echo "$exercise->condition"; ?></div>
                </div>
                <div class="answer clearfix">
                    <?php if($exercise->id_visual) : ?>
                        <?php $this->renderPartial("//exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$key, 'index'=>$key+1)); ?>
                    <?php endif; ?>
                </div>
                <input class="duration" type="hidden" name="Exercises[<?php echo $key; ?>][duration]" value="0" />
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    Нет заданий
<?php endif; ?>
<div class="control-buttons">
    <?php echo CHtml::link('Проверить результаты', '#', array('class'=>'send-result-button', 'tabindex'=>$key+2)); ?>
</div>
