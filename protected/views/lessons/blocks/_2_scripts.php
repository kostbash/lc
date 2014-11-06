<script type="text/javascript">
    $(function(){
        $('.accurate-answer').change(function() {
            setDuration(this);
            checkAccurateAnswer(this);
        });
        
        $('.dropdown-list').change(function() {
            setDuration(this);
            checkDropdownList(this);
        });

        $('.checkboxes , .radio-buttons').change(function() {
            setDuration(this);
            checkRadioAndCheckBox(this);
        });

        $('.pick-blocks .block').click(function() {
            answer = $(this);
            pickBlock = answer.closest('.pick-blocks');
            setDuration(answer);
            key = answer.data('key');
            $('.block[data-key=' + key + ']').removeClass('selected');
            answer.addClass('selected');
            hiddenAnswer = $('.hidden-answer[data-key=' + key + ']');
            hiddenAnswer.val(answer.data('val'));
            checkPickBlock(pickBlock);
        });

        $('.comparisons .list-one').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comp-answer',
            connectWith: '.list-one',
            update: function(event, ui) {
                current = $(this);
                setDuration(this);
                sender = $(ui.sender);
                item = $(ui.item);
                if(!sender.length)
                {
                    items = current.closest('.comparisons').find('.list-one .comp-answer');
                    lists = current.closest('.comparisons').find('.list-one');
                    lists.each(function(n, list){
                        list = $(list);
                        list.append(items.eq(n));
                    });
                }
            }
        });

        $('.comparisons .list-two').sortable({
            axis: 'y',
            cancel: null,
            cursor: 'move',
            items: '> .comp-answer',
            connectWith: '.list-two',
            update: function(event, ui) {
                current = $(this);
                setDuration(this);
                sender = $(ui.sender);
                item = $(ui.item);
                if(!sender.length)
                {
                    items = current.closest('.comparisons').find('.list-two .comp-answer');
                    lists = current.closest('.comparisons').find('.list-two');
                    lists.each(function(n, list){
                        list = $(list);
                        list.append(items.eq(n));
                    });
                }
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
                
                if(!words.find('.word').length)
                {
                   checkTextWithSpace(cont);
                }
            }
        });
        
        $('.text-with-limits').change(function() {
            current = $(this);
            setDuration(this);
            if(current.closest('.exercise').hasClass('without-answer'))
            {
                checkTextWithLimits(this);
            }
        });
        
        $('.exact-answers-with-space').change(function() {
            current = $(this);
            setDuration(this);
            if(current.closest('.exercise').hasClass('without-answer'))
            {
                checkExactAnswersWithSpace(this);
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
            checkPickArea(answer.closest('.pick-area'));
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
                item.removeClass('not-hide').addClass('hide');
                if(area.closest('.exercise').hasClass('without-answer'))
                {
                    checkHotmapItems(area.closest('.hotmap-items'));
                }
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
                if(cont.closest('.exercise').hasClass('without-answer'))
                {
                    checkBagType(cont.closest('.bags-type'));
                }
            }
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
                    count = cont.closest('.hotmap-bags').find('> .left .count');
                    count.html(count.html()-1);
                }
                cont.append(answer.css('left', 0).css('top',0));
                if(cont.closest('.exercise').hasClass('without-answer'))
                {
                    checkHotmapBags(cont.closest('.hotmap-bags'));
                }
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
                if(cont.closest('.exercise').hasClass('without-answer'))
                {
                    checkHotmapOrdering(cont.closest('.hotmap-ordering'));
                }   
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
    });
</script>