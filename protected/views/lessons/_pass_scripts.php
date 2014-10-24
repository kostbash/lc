<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js"); ?>
<script type="text/javascript">
    $(function(){
        $('.with-right').change(function(){
            current = $(this);
            links = current.closest('.export-button').find('.dropdown-menu li a');
            if(current.val()==1)
            {
                links.each(function(n, link){
                    link = $(link);
                    str = link.attr('href');
                    str = str.replace(/with_right=1/g, 'with_right=0');
                    link.attr('href', str);
                });
                current.val(0);
            }
            else
            {
                links.each(function(n, link){
                    link = $(link);
                    str = link.attr('href');
                    str = str.replace(/with_right=0/g, 'with_right=1');
                    link.attr('href', str);
                });
                current.val(1);
            }
        });
        

        $('.next-button, .send-result-button').click(function() {
            result = true;
            
            accurateAnswers = $('.accurate-answer');
            if (accurateAnswers.length)
            {
                accurateAnswers.each(function(n, accurateAnswer) {
                    if(!checkAccurateAnswer(accurateAnswer))
                    {
                        result = false;
                    }
                });
            }

            dropdownLists = $('.dropdown-list');
            if (dropdownLists.length)
            {
                dropdownLists.each(function(n, dropdownList) {
                    if(!checkDropdownList(dropdownList))
                    {
                        result = false;
                    }
                });
            }
            
            radiosAndCheckboxs = $('.radio-buttons, .checkboxes');
            if (radiosAndCheckboxs.length)
            {
                radiosAndCheckboxs.each(function(n, radioAndCheckbox) {
                    if(!checkRadioAndCheckBox(radioAndCheckbox))
                    {
                        result = false;
                    }
                });
            }
            
            pickBlocks = $('.pick-blocks');
            if (pickBlocks.length)
            {
                pickBlocks.each(function(n, pickBlock) {
                    if(!checkPickBlock(pickBlock))
                    {
                        result = false;
                    }
                });
            }
            
            textsWithSpaces = $('.text-with-space');
            if (textsWithSpaces.length)
            {
                textsWithSpaces.each(function(n, textWithSpace) {
                    if(!checkTextWithSpace(textWithSpace))
                    {
                        result = false;
                    }
                });
            }
            
            textsWithLimits = $('.text-with-limits');
            if (textsWithLimits.length)
            {
                textsWithLimits.each(function(n, textWithLimits) {
                    if(!checkTextWithLimits(textWithLimits))
                    {
                        result = false;
                    }
                });
            }
            
            exactAnswersWithSpaces = $('.exact-answers-with-space');
            if (exactAnswersWithSpaces.length)
            {
                exactAnswersWithSpaces.each(function(n, answersWithSpace) {
                    if(!checkExactAnswersWithSpace(answersWithSpace))
                    {
                        result = false;
                    }
                });
            }
            
            pickAreas = $('.pick-area');
            if (pickAreas.length)
            {
                pickAreas.each(function(n, pickArea) {
                    if(!checkPickArea(pickArea))
                    {
                        result = false;
                    }
                });
            }
            
            hotmapsItems = $('.hotmap-items');
            if (hotmapsItems.length)
            {
                hotmapsItems.each(function(n, hotmapItems) {
                    if(!checkHotmapItems(hotmapItems))
                    {
                        result = false;
                    }
                });
            }
            
            bagsType = $('.bags-type');
            if (bagsType.length)
            {
                bagsType.each(function(n, bagType) {
                    if(!checkBagType(bagType))
                    {
                        result = false;
                    }
                });
            }
            
            hotmapsBags = $('.hotmap-bags');
            if (hotmapsBags.length)
            {
                hotmapsBags.each(function(n, hotmapBags) {
                    if(!checkHotmapBags(hotmapBags))
                    {
                        result = false;
                    }
                });
            }
            
            hotmapsOrdering = $('.hotmap-ordering');
            if (hotmapsOrdering.length)
            {
                hotmapsOrdering.each(function(n, hotmapOrdering) {
                    if(!checkHotmapOrdering(hotmapOrdering))
                    {
                        result = false;
                    }
                });
            }

            if (result) {
                //$('#exercises-form').submit();
            } else {
                alert('Не для всех заданий даны ответы');
            }
            return false;
        });
    });

    function checkAccurateAnswer(accurateAnswer)
    {
        accurateAnswer = $(accurateAnswer);
        input = accurateAnswer.find('input');
        answerCont = accurateAnswer.closest('.answer');
        if (!$.trim(input.val()))
        {
            answerCont.addClass('no-selected');
            return false;
        } else {
            answerCont.removeClass('no-selected');
            return true;
        }
    }

    function checkDropdownList(dropdownList)
    {
        dropdownList = $(dropdownList);
        select = dropdownList.find('select');
        answerCont = dropdownList.closest('.answer');
        if (!$.trim(select.val()))
        {
            answerCont.addClass('no-selected');
            return false;
        } else {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    function checkRadioAndCheckBox(answer)
    {
        answer = $(answer);
        checked = answer.find('input:checked');
        answerCont = answer.closest('.answer');
        if (!checked.length)
        {
            answerCont.addClass('no-selected');
            return false;
        } else {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    function checkPickBlock(pickBlock)
    {
        pickBlock = $(pickBlock);
        input = pickBlock.find('.hidden-answer');
        answerCont = input.closest('.answer');
        if (!$.trim(input.val()))
        {
            answerCont.addClass('no-selected');
            return false;
        } else {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    function checkComparisons(comparisons)
    {
        
    }
    
    function checkOrderings(orderings)
    {
        
    }

    function checkTextWithSpace(textWithSpace)
    {
        textWithSpace = $(textWithSpace);
        answerCont = textWithSpace.closest('.answer');
        words = textWithSpace.find('.words .word');
        if (words.length)
        {
            answerCont.addClass('no-selected');
            return false;
        } else {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    function checkTextWithLimits(textWithLimits)
    {
        textWithLimits = $(textWithLimits);
        selects = textWithLimits.find('select');
        answerCont = textWithLimits.closest('.answer');
        res = true;
        selects.each(function(n, select) {
            select = $(select);
            if (!select.val())
            {
                res = false;
                return false;
            }
        });
        if (res)
            answerCont.removeClass('no-selected');
        else
            answerCont.addClass('no-selected');
        return res;
    }
    
    function checkExactAnswersWithSpace(answersWithSpace)
    {
        answersWithSpace = $(answersWithSpace);
        inputs = answersWithSpace.find('input');
        answerCont = answersWithSpace.closest('.answer');
        res = true;
        inputs.each(function(n, input) {
            input = $(input);
            if (!input.val())
            {
                res = false;
                return false;
            }
        });
        if (res)
            answerCont.removeClass('no-selected');
        else
            answerCont.addClass('no-selected');
        return res;
    }
    
    function checkPickArea(pickArea)
    {
        pickArea = $(pickArea);
        input = pickArea.find('.hidden-answer');
        answerCont = pickArea.closest('.answer');
        if (!$.trim(input.val()))
        {
            answerCont.addClass('no-selected');
            return false;
        } else {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    function checkHotmapItems(hotmapItems)
    {
        hotmapItems = $(hotmapItems);
        res = true;
        items = hotmapItems.find('.items .item');
        answerCont = hotmapItems.closest('.answer');
        items.each(function(n, item) {
            item = $(item);
            if (item.find('.hidden-answer').val() === '')
            {
                res = false;
                return false;
            }
        });
        if (res)
            answerCont.removeClass('no-selected');
        else
            answerCont.addClass('no-selected');
        return res;
    }
    
    function checkBagType(bag)
    {
        bag = $(bag);
        items = bag.find('.items .item');
        answerCont = bag.closest('.answer');
        if(items.length)
        {
            answerCont.addClass('no-selected');
            return false;
        }
        else
        {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    function checkHotmapBags(hotmapBags)
    {
        hotmapBags = $(hotmapBags);
        items = hotmapBags.find('.items .item');
        answerCont = hotmapBags.closest('.answer');
        if(items.length)
        {
            answerCont.addClass('no-selected');
            return false;
        }
        else
        {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    function checkHotmapOrdering(hotmapOrdering)
    {
        hotmapOrdering = $(hotmapOrdering);
        items = hotmapOrdering.find('.items .item');
        answerCont = hotmapOrdering.closest('.answer');
        if(items.length)
        {
            answerCont.addClass('no-selected');
            return false;
        }
        else
        {
            answerCont.removeClass('no-selected');
            return true;
        }
    }
    
    seconds = 0;
    
    function setDuration(exerciseItem)
    {
        exercise = $(exerciseItem).closest('.exercise');
        duration = exercise.find('.duration');
        newVal = parseInt(seconds, 10) + parseInt(duration.val(), 10);
        duration.val(newVal);
        seconds=0;
    }
    
    function countTime()
    {
        ++seconds;
    }
    setInterval('countTime()', 1000);
</script>