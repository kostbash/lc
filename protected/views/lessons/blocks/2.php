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
        
        $('.bags-type .item').draggable({cursor: 'move', revert:true});
        $('.bags-type .bag-drop').droppable({
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
                cont.append(answer);
                items = cont.closest('.bags-type').find('.items');
                bags = cont.closest('.bags-type').find('.bags');
                resultAnswer = cont.closest('.exercise').find('> .head .result');
                hiddenAnswer = answer.find('.hidden-answer');
                hiddenAnswer.val(cont.closest('.bag').data('index'));
            }
        });
        
        $('.hotmap-items .item').draggable({cursor: 'move', revert:true});
        
        $('.hotmap-items svg g').mouseover(function(){
            current = $(this);
            current.attr('class', 'area mouseover');
        });
        $('.hotmap-items svg g').mouseleave(function(){
            current = $(this);
            current.attr('class', 'area');
        });
        
        $('.hotmap-items svg g').droppable({
            accept: function(item){
                item_exericse_id = $(item).closest('.exercise').attr('id');
                drop_exericse_id = $(this).closest('.exercise').attr('id');
                return $(this).attr('class')==='area mouseover' && item_exericse_id === drop_exericse_id;
            },
            tolerance:'pointer',
            drop: function(event,info)
            {
                item = $(info.draggable);
                area = $(this);
                setDuration(area);
                id_area = area.data('id');
                hiddenAnswer = item.find('.hidden-answer');
                hiddenAnswer.val(id_area);
                item.css('display', 'none');
            }
        });
        
        $('.pick-area g').click(function(){
            answer = $(this);
            setDuration(answer);
            answerCont = answer.closest('.answer');
            exercise = answer.closest('.exercise');
            key = answer.data('key');
            answerCont.find('.pick-area g[data-key='+key+']').attr('class', '');
            answer.attr('class', 'selected');
            hiddenAnswer = answerCont.find('.hidden-answer[data-key='+key+']');
            hiddenAnswer.val(answer.data('val'));
            checkInput(hiddenAnswer);
        });
        
        
        $('.hotmap-bags .area').draggable({
            cursor: 'move',
            drag:function (event, ui){
                cont = $(this);
                item = cont.closest('.hotmap-bags').find('.items .item[data-area='+cont.data('id')+']');
                item.css('left', event.pageX - 7);
                item.css('top', event.pageY - 7);
                item.css('display', 'inline-block');
            },
            stop:function (event, ui){
                cont = $(this);
                item = cont.closest('.hotmap-bags').find('.items .item[data-area='+cont.data('id')+']');
                item.css('display', 'none');
            }
        });
        
        $('.hotmap-bags .item').draggable({cursor: 'move', revert:true});
        
        $('.hotmap-bags .bag-drop').droppable({
            accept: function(item){
                item_exericse_id = $(item).closest('.exercise').attr('id');
                drop_exericse_id = $(this).closest('.exercise').attr('id');
                return item_exericse_id === drop_exericse_id;
            },
            tolerance:'pointer',
            drop: function(event,info)
            {
                area = $(info.draggable);
                cont = $(this);
                setDuration(cont);
                items = cont.closest('.hotmap-bags').find('.items');
                if(area.hasClass('item'))
                {
                   answer = area;
                }
                else
                {
                    answer = items.find('.item[data-area='+area.data('id')+']');
                    area.draggable("disable");
                    area.attr('class', 'area disable');
                }
                cont.append(answer.css('left', 0).css('top',0));
            }
        });
        
        $('.hotmap-bags .bag-drop .item').live('mouseover',function(){
            current = $(this);
            area = current.closest('.hotmap-bags').find('.area[data-id='+current.data('area')+']');
            area.attr('class', 'area visible');
        });
        $('.hotmap-bags .bag-drop .item').live('mouseleave',function(){
            current = $(this);
            area = current.closest('.hotmap-bags').find('.area[data-id='+current.data('area')+']');
            area.attr('class', 'area disable');
        });
        
        $('.hotmap-items  .item').draggable({cursor: 'move', revert:true});
        
        $('.hotmap-items svg g').mouseover(function(){
            current = $(this);
            current.attr('class', 'area mouseover');
        });
        $('.hotmap-items svg g').mouseleave(function(){
            current = $(this);
            current.attr('class', 'area');
        });
        
        $('.hotmap-items svg g').droppable({
            accept: function(item){
                item_exericse_id = $(item).closest('.exercise').attr('id');
                drop_exericse_id = $(this).closest('.exercise').attr('id');
                return $(this).attr('class')==='area mouseover' && item_exericse_id === drop_exericse_id;
            },
            tolerance:'pointer',
            drop: function(event,info)
            {
                item = $(info.draggable);
                area = $(this);
                setDuration(area);
                id_area = area.data('id');
                resultAnswer = item.closest('.exercise').find('> .head .result');
                hiddenAnswer = item.find('.hidden-answer');
                hiddenAnswer.val(id_area);
                item.removeClass('not-hide').addClass('hide');
            }
        });
        
        $('.hotmap-ordering .area').draggable({
            cursor: 'move',
            drag:function (event, ui){
                cont = $(this);
                item = cont.closest('.hotmap-ordering').find('.items .item[data-area='+cont.data('id')+']');
                item.css('left', event.pageX - 7);
                item.css('top', event.pageY - 7);
                item.css('display', 'block');
            },
            stop:function (event, ui){
                cont = $(this);
                item = cont.closest('.hotmap-ordering').find('.items .item[data-area='+cont.data('id')+']');
                item.css('display', 'none');
            }
        });
        
        $('.hotmap-ordering .bag-drop .dropped-items').sortable({
            cancel: null,
            cursor: 'move',
            items: '> .item',
            tolerance: 'pointer',
            containment: 'parent',
            update: function(event, ui) {
                current = $(this);
                setDuration(current);
            }
        });
        
        $('.hotmap-ordering .bag-drop').droppable({
            accept: function(item){
                item_exericse_id = $(item).closest('.exercise').attr('id');
                drop_exericse_id = $(this).closest('.exercise').attr('id');
                return $(item).get(0).tagName === 'g' && item_exericse_id === drop_exericse_id;
            },
            tolerance:'pointer',
            drop: function(event,info)
            {
                area = $(info.draggable);
                cont = $(this);
                setDuration(cont);
                items = cont.closest('.hotmap-ordering').find('.items');
                answer = items.find('.item[data-area='+area.data('id')+']');
                area.draggable("disable");
                area.attr('class', 'area disable');
                cont.find('.dropped-items').append(answer.css('left', 0).css('top',0));
            }
        });
        
        $('.hotmap-ordering .bag-drop .item').live('mouseover',function(){
            current = $(this);
            area = current.closest('.hotmap-ordering').find('.area[data-id='+current.data('area')+']');
            area.attr('class', 'area visible');
        });
        $('.hotmap-ordering .bag-drop .item').live('mouseleave',function(){
            current = $(this);
            area = current.closest('.hotmap-ordering').find('.area[data-id='+current.data('area')+']');
            area.attr('class', 'area disable');
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
