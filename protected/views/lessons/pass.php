<script type="text/javascript">
    $(function(){
        <?php if($exerciseGroup->type != 2) : ?>
            $('.exercise .answer input').change(function(){
                current = $(this);
                if(current.val()=='')
                    current.siblings('.result').html('');
                else
                    $.ajax({
                        url: '<?php echo $this->createUrl('/exercises/right', array('id_relation'=>$userAndExerciseGroup->id)); ?>',
                        type:'POST',
                        data: current.serialize(),
                        success: function(result) { 
                                    if(result==1)
                                        current.siblings('.result').removeClass('color-unright').addClass('color-right').html('Верно');
                                    else if(result==0)
                                        current.siblings('.result').removeClass('color-right').addClass('color-unright').html('Не верно');
                         }
                    });
            });
            $('.exercise .answer input').keyup(function(){
                showButton = true;
                $('.exercise .answer input').each(function(){
                   if(!$.trim($(this).val()))
                       showButton = false;
                });
                if(showButton) {
                    $.ajax({
                        url: '<?php echo $this->createUrl('/lessons/saverightanswers', array('user_lesson'=>$userLesson->id, 'group'=>$userAndExerciseGroup->id_exercise_group)); ?>',
                        type:'POST',
                        data: $('#exercises-form').serialize()
                    });
                    $('.nextGroup').removeAttr('disabled');
                } else
                    $('.nextGroup').attr('disabled', 'disabled');
            });
            
        <?php else : ?>
            $('.exercise .answer input').change(function(){
                if($(this).val()) {
                    $(this).closest('.answer').removeClass('has-error has-feedback');
                    $(this).siblings('.form-control-feedback').remove();
                }
                else {
                    $(this).closest('.answer').addClass('has-error has-feedback').append('<span class="glyphicon glyphicon-pencil form-control-feedback"></span>');
                }
            });
            
            $('#exercises-form input[type=submit]').click(function(){
                can = true;
                $('.exercise .answer input').each(function(){
                   if(!$.trim($(this).val()))
                    {
                        can = false;
                        $(this).closest('.answer').addClass('has-error has-feedback').append('<span class="glyphicon glyphicon-pencil form-control-feedback"></span>');
                    }
                });
                if(!can) {
                    alert('Не для всех заданий даны ответы');
                    return false;
                }
            });
        <?php endif; ?>
        $('.exercise .answer input').keydown(function(e){
              if(e.keyCode==13){
                nextTab = $('input[tabindex='+(parseInt($(this).attr('tabindex'))+1)+']');
                nextTab.focus();
                return false;
              }
         });
        
        $('#skills').popover();
    });
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
                <?php foreach($exerciseGroup->ExercisesRaw as $i => $exercise) : $classExercise = (++$i%2)==0 ? ' gray' : ''; ?>
                    <div class="exercise clearfix<?php echo $classExercise; ?>">
                        <h2><?php if($exercise->need_answer) echo "Задание $posTest:"; else echo 'Теория:'; ?></h2>
                        <?php if($exercise->need_answer) : ++$posTest; ?>
                            <div class="question"><?php echo "$exercise->question"; ?></div>
                            <div class="answer clearfix" style="display: inline-block;">
                                    <?php echo CHtml::textField("Exercises[$exercise->id][answer]", '', array('placeholder'=>'Введите ответ', 'class'=>'form-control', 'tabindex'=>$posTest, 'autocomplete'=>'off')); ?>
                                    <div class="result"></div>
                                    <?php $userExercise = UserAndExercises::model()->findByAttributes(array('id_relation'=>$userAndExerciseGroup->id, 'id_exercise'=>$exercise->id));
                                        if($userExercise) echo "<span class='label label-info'>Последний ответ: $userExercise->last_answer</span>";
                                    ?>
                            </div>
                        <?php else : ?>
                            <?php echo $exercise->question; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php elseif($exerciseGroup->type == 2 && $criteriaExercises) : $position=0; ?>
                <?php foreach($criteriaExercises as $key => $exercise) : $classExercise = (++$i%2)==0 ? ' gray' : ''; $position++; ?>
                <div class="exercise clearfix<?php echo $classExercise; ?>">
                    <h2><?php echo "Задание $position:"; ?></h2>
                    <div class="question"><?php echo "$exercise->question"; ?></div>
                    <div class="answer clearfix">
                        <?php echo CHtml::textField("Exercises[$key][answer]", '', array('placeholder'=>'Введите ответ', 'class'=>'form-control', 'style'=>'width:100%', 'tabindex'=>$key, 'autocomplete'=>'off')); ?>
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
                <?php if($userAndExerciseGroup->nextGroup) echo CHtml::link('Перейти к следующей группе заданий<i class="glyphicon glyphicon-arrow-right"></i>', array('lessons/nextgroup', 'id'=>$userAndExerciseGroup->id), array('class'=>'btn btn-success btn-icon-right nextGroup', 'style'=>'margin-top: 20px', 'disabled'=>'disabled'));
                    elseif($userLesson->Lesson->accessNextLesson($userLesson->id)) echo CHtml::link('Следующий урок<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'btn btn-success btn-icon-right nextGroup', 'style'=>'margin-top: 20px', 'disabled'=>'disabled')); ?>
                </div>
            <?php endif; ?>
            <?php else : ?>
            Нет группы заданий
            <?php endif; ?>
            </div>
        </div>
</div>
</div>
