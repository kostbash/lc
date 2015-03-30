<script type="text/javascript">
$(function() {
    $('.accurate-answer').change(function() {
        setDuration(this);
        ResultAnswer(this);
        checkAccurateAnswer(this);
    });

    $('.dropdown-list').change(function() {
        setDuration(this);
        ResultAnswer(this);
        checkDropdownList(this);
    });

    $('.checkboxes , .radio-buttons').change(function() {
        setDuration(this);
        ResultAnswer(this);
        checkRadioAndCheckBox(this);
    });

    $('.pick-blocks .block').click(function() {
        answer = $(this);
        pickBlock = answer.closest('.pick-blocks');
        setDuration(answer);
        key = answer.data('key');
        $('.block[data-key=' + key + ']').removeClass('selected');
        answer.addClass('selected');
        hiddenAnswer = $('.hidden-answer[data-key=' + key + ']')
        hiddenAnswer.val(answer.data('val'));
        resultAnswer = answer.closest('.exercise').find('> .head .result');
        $.ajax({
            url: '<?php echo $this->createUrl('/exercises/right'); ?>',
            type: 'POST',
            data: hiddenAnswer.serialize(),
            success: function(result) {
                if (result == 1)
                    resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                else if (result == 0)
                    resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
            }
        });
        checkPickBlock(pickBlock);
    });

    $('.comparisons .list-one').sortable({
        axis: 'y',
        cancel: null,
        cursor: 'move',
        connectWith: '.list-one',
        items: '> .comp-answer',
        update: function(event, ui) {
            current = $(this);
            setDuration(current);
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
            resultAnswer = current.closest('.exercise').find('> .head .result');
            hiddenAnswer = current.closest('.answer').find('.hidden-answer');
            reachGoal('SentAnswer');
            $.ajax({
                url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                type: 'POST',
                data: hiddenAnswer.serialize(),
                success: function(result) {
                    if (result == 1)
                    {
                        reachGoal('CorrectAnswer');
                        resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                    }
                    else if (result == 0)
                        resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                }
            });
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
            setDuration(current);
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
            resultAnswer = current.closest('.exercise').find('> .head .result');
            hiddenAnswer = current.closest('.answer').find('.hidden-answer');
            reachGoal('SentAnswer');
            $.ajax({
                url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                type: 'POST',
                data: hiddenAnswer.serialize(),
                success: function(result) {
                    if (result == 1)
                    {
                        reachGoal('CorrectAnswer');
                        resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                    }
                    else if (result == 0)
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
            reachGoal('SentAnswer');
            $.ajax({
                url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                type: 'POST',
                data: hiddenAnswer.serialize(),
                success: function(result) {
                    if (result == 1)
                    {
                        reachGoal('CorrectAnswer');
                        resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                    }
                    else if (result == 0)
                        resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                }
            });
        }
    });

    $('.text-with-space .word').draggable({cursor: 'move', revert: true});
    $('.text-with-space .answer-droppable').droppable({
        accept: function(item) {
            item_exericse_id = $(item).closest('.exercise').attr('id');
            drop_exericse_id = $(this).closest('.exercise').attr('id');
            return item_exericse_id === drop_exericse_id;
        },
        tolerance: 'pointer',
        drop: function(event, info)
        {
            answer = $(info.draggable);
            cont = $(this);
            setDuration(cont);
            words = cont.closest('.text').siblings('.words');
            existWord = cont.find('.word');
            if (existWord.length)
            {
                words.append(existWord);
            }
            cont.append(answer);
            resultAnswer = cont.closest('.exercise').find('> .head .result');
            hiddenAnswer = cont.closest('.text').find('.hidden-answer');
            if (!words.find('.word').length)
            {
                reachGoal('SentAnswer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type: 'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) {
                        if (result == 1)
                        {
                            reachGoal('CorrectAnswer');
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        }
                        else if (result == 0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                        else
                            alert(result);
                    }
                });
                checkTextWithSpace(cont);
            }
            else
            {
                resultAnswer.html('');
            }
        }
    });

    $('.text-with-limits').change(function() {
        current = $(this);
        setDuration(this);
        ResultAnswer(this);
        if(current.closest('.exercise').hasClass('without-answer'))
        {
            checkTextWithLimits(this);
        }
    });

    $('.exact-answers-with-space').change(function() {
        current = $(this);
        setDuration(this);
        ResultAnswer(this);
        if(current.closest('.exercise').hasClass('without-answer'))
        {
            checkExactAnswersWithSpace(this);
        }
    });

    $('.pick-area g').click(function() {
        answer = $(this);
        setDuration(answer);
        answerCont = answer.closest('.answer');
        exercise = answer.closest('.exercise');
        key = answer.data('key');
        answerCont.find('.pick-area g[data-key=' + key + ']').attr('class', '');
        answer.attr('class', 'selected');
        hiddenAnswer = answerCont.find('.hidden-answer[data-key=' + key + ']');
        hiddenAnswer.val(answer.data('val'));

        resultAnswer = answer.closest('.exercise').find('> .head .result');
        reachGoal('SentAnswer');
        $.ajax({
            url: '<?php echo $this->createUrl('/exercises/right'); ?>',
            type: 'POST',
            data: hiddenAnswer.serialize(),
            success: function(result) {
                if (result == 1)
                {
                    reachGoal('CorrectAnswer');
                    resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                }
                else if (result == 0)
                    resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                else
                    alert(result);
            }
        });
        checkPickArea(answer.closest('.pick-area'));
    });

    $('.hotmap-items  .item').draggable({
        cursor: 'move',
        revert: true,
        drag: function(event, ui) {
            item = $(this);
            image = item.find('.image');
            if(image.length)
            {
                if(image.css('display')==='block')
                {
                    item.width(item.width());
                    image.hide(300);
                }
                ui.position.top += (event.pageY-ui.offset.top)-10;
            }
        },
        stop: function(event, ui) {
            item = $(this);
            image = item.find('.image');
            if(image.length)
            {
                image.show(300);
            }
        }
    });

    $('.hotmap-items  .item').dblclick(function(){
        current = $(this);
        if(!current.hasClass('without-area'))
        {
            hiddenAnswer = current.find('.hidden-answer');
            current.addClass('without-area');
            current.css('color', '').css('borderColor', '');
            id_area = hiddenAnswer.val();
            hiddenAnswer.val('');
            itemsWithArea = current.closest('.items').find('.hidden-answer[value="'+id_area+'"]');
            if(!itemsWithArea.length)
            {
                area = current.closest('.hotmap-items').find('.area[data-id="'+id_area+'"]');
                area.attr('class', 'area');
                area.removeAttr('data-hasitems');
            }
        }
    });

    $('.hotmap-items svg g').mouseover(function() {
        current = $(this);
        current.attr('class', 'area mouseover');
    });
    $('.hotmap-items svg g').mouseleave(function() {
        current = $(this);
        if(current.attr('data-hasitems')=="true")
        {
            current.attr('class', 'area visible');
        }
        else
        {
            current.attr('class', 'area');
        }
    });

    $('.hotmap-items svg g').droppable({
        accept: function(item) {
            item_exericse_id = $(item).closest('.exercise').attr('id');
            drop_exericse_id = $(this).closest('.exercise').attr('id');
            return $(this).attr('class') === 'area mouseover' && item_exericse_id === drop_exericse_id;
        },
        tolerance: 'pointer',
        drop: function(event, info)
        {
            item = $(info.draggable);
            area = $(this);
            setDuration(area);
            id_area = area.data('id');
            area_color = area.data('color');
            resultAnswer = item.closest('.exercise').find('> .head .result');
            hiddenAnswer = item.find('.hidden-answer');
            hiddenAnswer.val(id_area);

            allAnswers = item.closest('.items').find('.hidden-answer');
            item.css('color', area_color).css('borderColor', area_color).removeClass('without-area');
            area.attr('data-hasitems', true);

            if (!item.closest('.items').find('.item.without-area').length)
            {
                reachGoal('SentAnswer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type: 'POST',
                    data: allAnswers.serialize(),
                    success: function(result) {
                        if (result == 1)
                        {
                            reachGoal('CorrectAnswer');
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        }
                        else if (result == 0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                        else
                            alert(result);
                    }
                });

                if(area.closest('.exercise').hasClass('without-answer'))
                {
                    checkHotmapItems(area.closest('.hotmap-items'));
                }
            }
            else
            {
                resultAnswer.html('');
            }
        }
    });

    $('.bags-type .item').draggable({cursor: 'move', revert: true});
    $('.bags-type .bag-drop').droppable({
        accept: function(item) {
            item_exericse_id = $(item).closest('.exercise').attr('id');
            drop_exericse_id = $(this).closest('.exercise').attr('id');
            return item_exericse_id === drop_exericse_id;
        },
        tolerance: 'pointer',
        drop: function(event, info)
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
            if (!items.find('.item').length)
            {
                reachGoal('SentAnswer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type: 'POST',
                    data: bags.find('.hidden-answer').serialize(),
                    success: function(result) {
                        if (result == 1)
                        {
                            reachGoal('CorrectAnswer');
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        }
                        else if (result == 0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                        else
                            alert(result);
                    }
                });
                checkBagType(cont.closest('.bags-type'));
            }
            else
            {
                resultAnswer.html('');
            }
        }
    });

    $('.hotmap-bags .area').draggable({
        cursor: 'move',
        drag: function(event, ui) {
            cont = $(this);
            item = cont.closest('.hotmap-bags').find('.items .item[data-area=' + cont.data('id') + ']');
            item.css('left', event.pageX - 7);
            item.css('top', event.pageY - 7);
            item.css('display', 'inline-block');
        },
        stop: function(event, ui) {
            cont = $(this);
            item = cont.closest('.hotmap-bags').find('.items .item[data-area=' + cont.data('id') + ']');
            item.css('display', 'none');
        }
    });

    $('.hotmap-bags .item').draggable({cursor: 'move', revert: true});

    $('.hotmap-bags .bag-drop').droppable({
        accept: function(item) {
            item_exericse_id = $(item).closest('.exercise').attr('id');
            drop_exericse_id = $(this).closest('.exercise').attr('id');
            return item_exericse_id === drop_exericse_id;
        },
        tolerance: 'pointer',
        drop: function(event, info)
        {
            area = $(info.draggable);
            cont = $(this);
            setDuration(cont);
            items = cont.closest('.hotmap-bags').find('.items');
            if (area.hasClass('item'))
            {
                answer = area;
            }
            else
            {
                answer = items.find('.item[data-area=' + area.data('id') + ']');
                area.draggable("disable");
                area.attr('class', 'area disable');
                count = cont.closest('.hotmap-bags').find('> .left .count');
                count.html(count.html()-1);
            }
            cont.append(answer.css('left', 0).css('top', 0));
            bags = cont.closest('.hotmap-bags').find('.bags');
            resultAnswer = cont.closest('.exercise').find('> .head .result');
            hiddenAnswer = answer.find('.hidden-answer');
            hiddenAnswer.val(cont.closest('.bag').data('index'));
            if (!items.find('.item').length)
            {
                reachGoal('SentAnswer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type: 'POST',
                    data: bags.find('.hidden-answer').serialize(),
                    success: function(result) {
                        if (result == 1)
                        {
                            reachGoal('CorrectAnswer');
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        }
                        else if (result == 0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                        else
                            alert(result);
                    }
                });
                checkHotmapBags(cont.closest('.hotmap-bags'));
            }
            else
            {
                resultAnswer.html('');
            }
        }
    });

    $('.hotmap-bags .bag-drop .item').live('mouseover', function() {
        current = $(this);
        area = current.closest('.hotmap-bags').find('.area[data-id=' + current.data('area') + ']');
        area.attr('class', 'area visible');
    });

    $('.hotmap-bags .bag-drop .item').live('dblclick', function() {
        current = $(this);
        items = cont.closest('.hotmap-bags').find('.items');
        current.find('.hidden-answer').val('');
        items.append(current);
        current.css('display', 'none');
        area = current.closest('.hotmap-bags').find('.area[data-id=' + current.data('area') + ']');
        area.attr('class', 'area');
        area.draggable('enable');
        count = current.closest('.hotmap-bags').find('> .left .count');
        count.html(parseInt(count.html(), 10)+1);
    });

    $('.hotmap-bags .bag-drop .item').live('mouseleave', function() {
        current = $(this);
        area = current.closest('.hotmap-bags').find('.area[data-id=' + current.data('area') + ']');
        area.attr('class', 'area disable');
    });

    $('.hotmap-bags svg g:not(.aria-disabled)').live('mouseover', function() {
        current = $(this);
        current.attr('class', 'area disable');
    });

    $('.hotmap-bags svg g:not(.aria-disabled)').live('mouseleave', function() {
        current = $(this);
        current.attr('class', 'area');
    });

    $('.hotmap-bags svg g[aria-disabled=true]').live('mouseover', function() {
        current = $(this);
        current.attr('class', 'area');
    });

    $('.hotmap-bags svg g[aria-disabled=true]').live('mouseleave', function() {
        current = $(this);
        current.attr('class', 'area disable');
    });

    $('.hotmap-ordering .area').draggable({
        cursor: 'move',
        drag: function(event, ui) {
            cont = $(this);
            item = cont.closest('.hotmap-ordering').find('.items .item[data-area=' + cont.data('id') + ']');
            item.css('left', event.pageX - 7);
            item.css('top', event.pageY - 7);
            item.css('display', 'block');
        },
        stop: function(event, ui) {
            cont = $(this);
            item = cont.closest('.hotmap-ordering').find('.items .item[data-area=' + cont.data('id') + ']');
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
            resultAnswer = current.closest('.exercise').find('> .head .result');
            hiddenAnswer = current.closest('.answer').find('.hidden-answer');
            items = current.closest('.hotmap-ordering').find('.items');
            if (!items.find('.item').length)
            {
                reachGoal('SentAnswer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type: 'POST',
                    data: hiddenAnswer.serialize(),
                    success: function(result) {
                        if (result == 1)
                        {
                            reachGoal('CorrectAnswer');
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        }
                        else if (result == 0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                    }
                });
            }
        }
    });

    $('.hotmap-ordering .bag-drop').droppable({
        accept: function(item) {
            item_exericse_id = $(item).closest('.exercise').attr('id');
            drop_exericse_id = $(this).closest('.exercise').attr('id');
            return $(item).get(0).tagName === 'g' && item_exericse_id === drop_exericse_id;
        },
        tolerance: 'pointer',
        drop: function(event, info)
        {
            area = $(info.draggable);
            cont = $(this);
            setDuration(cont);
            items = cont.closest('.hotmap-ordering').find('.items');
            answer = items.find('.item[data-area=' + area.data('id') + ']');
            area.draggable("disable");
            area.attr('class', 'area disable');
            cont.find('.dropped-items').append(answer.css('left', 0).css('top', 0));
            bag = cont.closest('.hotmap-ordering').find('.bag');
            resultAnswer = cont.closest('.exercise').find('> .head .result');
            hiddenAnswer = answer.find('.hidden-answer').val(1);

            count = cont.closest('.hotmap-ordering').find('> .left .count');
            count.html(parseInt(count.html(), 10)-1);
            if (!items.find('.item').length)
            {
                reachGoal('SentAnswer');
                $.ajax({
                    url: '<?php echo $this->createUrl('/exercises/right'); ?>',
                    type: 'POST',
                    data: bag.find('.hidden-answer').serialize(),
                    success: function(result) {
                        if (result == 1)
                        {
                            reachGoal('CorrectAnswer');
                            resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                        }
                        else if (result == 0)
                            resultAnswer.removeClass('right').addClass('unright').html('НЕ ВЕРНО !');
                        else
                            alert(result);
                    }
                });
                checkHotmapOrdering(cont.closest('.hotmap-ordering'));
            }
            else
            {
                resultAnswer.html('');
            }
        }
    });

    $('.hotmap-ordering .bag-drop .item').live('mouseover', function() {
        current = $(this);
        area = current.closest('.hotmap-ordering').find('.area[data-id=' + current.data('area') + ']');
        area.attr('class', 'area visible');
    });
    $('.hotmap-ordering .bag-drop .item').live('mouseleave', function() {
        current = $(this);
        area = current.closest('.hotmap-ordering').find('.area[data-id=' + current.data('area') + ']');
        area.attr('class', 'area disable');
    });

    $('.hotmap-ordering svg g:not(.aria-disabled)').live('mouseover', function() {
        current = $(this);
        current.attr('class', 'area disable');
    });

    $('.hotmap-ordering svg g:not(.aria-disabled)').live('mouseleave', function() {
        current = $(this);
        current.attr('class', 'area');
    });

    $('.hotmap-ordering svg g[aria-disabled=true]').live('mouseover', function() {
        current = $(this);
        current.attr('class', 'area');
    });

    $('.hotmap-ordering svg g[aria-disabled=true]').live('mouseleave', function() {
        current = $(this);
        current.attr('class', 'area disable');
    });

    $('.universal').change(function() {
        current = $(this);
        setDuration(this);
        ResultAnswer(this);
        if(current.closest('.exercise').hasClass('without-answer'))
        {
            checkUniversal(this);
        }
    });
});

function ResultAnswer(container)
{
    current = $(container);
    answers = current.closest('.answer').find('input, select');
    resultAnswer = current.closest('.exercise').find('> .head .result');
    send = true;
    answers.each(function(n, answer)
    {
        answer = $(answer);
        if(!answer.val())
        {
            send = false;
            return false;
        }
    });

    if(send)
    {
        reachGoal('SentAnswer');
        $.ajax({
            url: '<?php echo $this->createUrl('/exercises/right'); ?>',
            type: 'POST',
            data: answers.serialize(),
            success: function(result) {
                if (result == 1)
                {
                    reachGoal('CorrectAnswer');
                    resultAnswer.removeClass('unright').addClass('right').html('ВЕРНО !');
                }
                else if (result == 0)
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

</script>

<?php if ($tasks) : $posTest = 1; ?>
    <div id="exercises">
        <?php foreach ($tasks as $i => $exercise) : ?>
            <div id="exercise_<?php echo $i; ?>"  class="exercise <?php echo ( ++$i % 2) == 0 ? 'even' : 'odd'; ?>">
                <div class="head clearfix">
                    <div class="number"><?php echo $posTest++; ?></div>
                    <div class="condition">
                        <?php $gw = new GraphicWidgets($exercise->condition);?>
                        <?php echo $gw->draw();?>
                    </div>
                    <div class="result"></div>
                </div>
                <div class="answer clearfix">
                    <?php if ($exercise->id_visual) : ?>
                        <?php $this->renderPartial("//exercises/visualizations/{$exercise->id_visual}", array('model' => $exercise, 'key' => $exercise->id, 'index' => $i)); ?>
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
    <?php echo CHtml::link('К следующему блоку', array('lessons/nextblock', 'id'=>$course_id), array('tabindex'=>count($tasks)+1, 'class'=>'next-button')); ?>
</div>