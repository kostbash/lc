<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js"); ?>
<script type="text/javascript">
    $(function(){
        <?php if($exerciseGroup->type != 2) : ?>
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
                    $.ajax({
                        url: '<?php echo $this->createUrl('/lessons/saverightanswers', array('user_lesson'=>$userLesson->id, 'group'=>$userAndExerciseGroup->id_exercise_group)); ?>',
                        type:'POST',
                        data: $('#exercises-form').serialize(),
                        dataType: 'json',
                        success: function(res) {
                            if(res.success)
                            {
                                result = true;
                            } else {
                                result = false;
                            }
                        }
                    });
                } else {
                    alert('Не для всех заданий даны ответы');
                }
                
                return result;
            });
            
            $('.for-editor-field').click(function(){
                answer = $(this);
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
            
        <?php else : ?>
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
                items: '> .comparison'
            });

            $('.comparisons .list-two').sortable({
                axis: 'y',
                cancel: null,
                cursor: 'move',
                items: '> .comparison'
            });
            
            $('.orderings ul').sortable({
                cancel: null,
                cursor: 'move',
                items: '> .word',
                containment: 'parent'
            });
            $('.text-with-space .word').draggable({cursor: 'move', revert:true});
            $('.text-with-space .answer-droppable').droppable({
                accept:'.text-with-space .word',
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
        <?php endif; ?>
            
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
            checkInput(this);
        });

        $('.checkbox-answer , .radio-answer').change(function(){
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

<div class="pass-lesson">
<div class="header-lesson clearfix">
    <div class="row lesson-header">
        <div class="names col-lg-9 col-md-9">
            <?php
                $contentSkills = "<table class='table table-hover' style='text-align: center'>
                    <thead>
                        <tr>
                            <th style='text-align: center'>Умение</th>
                            <th style='text-align: center'>Результат</th>
                        </tr>
                    </thead>
                    <tbody>";
                        if($userLesson->Lesson->Skills) {
                            foreach($userLesson->Lesson->Skills as $lessonSkill) {
                                $contentSkills .= "
                                    <tr>
                                        <td>$lessonSkill->name</td>
                                        <td>" . MyWidgets::ProgressBarWithLimiter(Lessons::PercentNeedBySkill($userLesson->id_lesson, $lessonSkill->id), Lessons::ProgressSkill($userLesson->id, $lessonSkill->id)) . "</td>
                                    </tr>";
                            }
                        } else {
                                $contentSkills .= "<tr><td colspan='2'>Нет умений</td></tr>";
                        }
                    $contentSkills .= "</tbody></table>";
            ?>
            <h1><?php echo "Урок $userLesson->position: ".$userLesson->Lesson->name; ?> <i style="font-size:16px;" id="skills" class="glyphicon glyphicon-tasks" data-toggle="popover" data-trigger ="hover" data-title = "Умения" data-html = 'true' data-container=".header-lesson" data-placement="bottom" data-content="<?php echo $contentSkills; ?>">
            </i></h1>
            <?php echo CHtml::link("Курс: ".$userLesson->Course->name, array('courses/index', 'id'=>$userLesson->Course->id)); ?>
        </div>
        <div class="next col-lg-3 col-md-3">
            <?php if($userLesson->Lesson->accessNextLesson($userLesson->id)) echo CHtml::link('Следующий урок<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'btn btn-success btn-icon-right')); ?>
        </div>
    </div>
</div>
<div class="row" style="position: relative">
        <div class="exercises">
            <div class="widget">
             <div class="list-exercises">
                <h2>Блоки урока</h2>
                <ul>
                    <?php if($userLesson->Lesson->ExercisesGroups) : ?>
                        <?php foreach($userLesson->Lesson->ExercisesGroups as $pos => $group) : ++$pos; ?>
                            <?php if(UserAndExerciseGroups::ExistUserAndGroup($userLesson->id, $group->id)) : ?>
                                <?php
                                    if($exerciseGroup->id == $group->id)
                                    {
                                        $class = 'link current';
                                        $currentPos = $pos;
                                    } else {
                                        $class = 'link';
                                    }
                                ?>
                                <li><?php if($group->type==2) echo 'Тест: '; echo CHtml::link("$pos.$group->name", array('lessons/pass', 'id'=>$userLesson->id, 'group'=>$group->id), array('class'=>$class)); ?></li>
                            <?php else : ?>
                                    <li><?php if($group->type==2) echo 'Тест: '; echo "$pos.$group->name"; ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li>Нет блоков</li>
                    <?php endif; ?>
                </ul>
            </div>
                
        <?php if($exerciseGroup) : ?>
            <h2><?php echo "Блок $currentPos: $exerciseGroup->name"; ?></h2>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'exercises-form',
                'enableAjaxValidation'=>false,
            )); ?>
            <?php if($exerciseGroup->type != 2 && $exerciseGroup->Exercises) : $posTest = 1; ?>
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
                    </div>
                <?php endforeach; ?>
            <?php elseif($exerciseGroup->type == 2 && $exercisesTest) : $position=0; ?>
                <?php foreach($exercisesTest as $key => $exercise) : $classExercise = (++$i%2)==0 ? ' gray' : ''; $position++; ?>
                    <div class="exercise clearfix<?php echo $classExercise; ?>">
                        <h2><?php echo "Задание $position:"; ?></h2>
                        <div class="question"><?php echo "$exercise->condition"; ?></div>
                        <div class="answer clearfix">
                            <?php if($exercise->id_visual) $this->renderPartial("/exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$key, 'index'=>$key)); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class='control-buttons'><?php echo CHtml::submitButton('Отправить результаты', array('class'=>'btn btn-success', 'style'=>'margin-top: 20px')); ?></div>
            <?php else : ?>
                Нет заданий
            <?php endif; ?>
            <?php $this->endWidget(); ?>
            <?php if($exerciseGroup->type != 2) : ?>
                <div class='control-buttons'>
                <?php if($userAndExerciseGroup->nextGroup) echo CHtml::link('Перейти к следующей группе заданий<i class="glyphicon glyphicon-arrow-right"></i>', array('lessons/nextgroup', 'id'=>$userAndExerciseGroup->id), array('class'=>'btn btn-success btn-icon-right nextGroup', 'style'=>'margin-top: 20px'));
                    elseif($userLesson->Lesson->accessNextLesson($userLesson->id)) echo CHtml::link('Следующий урок<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'btn btn-success btn-icon-right nextGroup', 'style'=>'margin-top: 20px')); ?>
                </div>
            <?php endif; ?>
        <?php else : ?>
            Нет группы заданий
        <?php endif; ?>
            </div>
        </div>
</div>
</div>
