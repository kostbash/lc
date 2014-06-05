<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.splitter-0.8.0.js"); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.splitter.css" />
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
            $('.exercise .answer input').change(function(){
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
                    $('.nextGroup').removeClass('hide');
                } else
                    $('.nextGroup').addClass('hide');
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
    });
</script>
<div class="pass-lesson">
<div class="header-lesson clearfix">
    <div class="row lesson-header">
        <div class="names col-lg-9 col-md-9">
            <h1><?php echo "Урок $userLesson->position: ".$userLesson->Lesson->name; ?></h1>
            <?php echo CHtml::link("Курс: ".$userLesson->Course->name, array('courses/index', 'id'=>$userLesson->Course->id)); ?>
        </div>
        <div class="skills col-lg-9 col-md-9">
            <ul>
            <?php
                foreach($userLesson->Lesson->Skills as $lessonSkill)
                    echo "<li><span class='label label-default'>$lessonSkill->name: достигнуто: ".Lessons::ProgressSkill($userLesson->id, $lessonSkill->id)."%, требуется: ".Lessons::PercentNeedBySkill($userLesson->id_lesson, $lessonSkill->id)."%</span></li>";
            ?>
            </ul>
        </div>
        <div class="next col-lg-3 col-md-3">
            <?php if($userLesson->Lesson->accessNextLesson($userLesson->id)) echo CHtml::link('Следующий урок<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'btn btn-success btn-icon-right')); ?>
        </div>
    </div>
</div>
<div class="row" style="position: relative">
    <div class="col-lg-7 col-md-7 exercises">
        <div class="widget">
         <div class="list-exercises">
            <h2>Задания урока</h2>
            <ul>
            <?php if($userLesson->Lesson->ExercisesGroups) : ?>
                <?php foreach($userLesson->Lesson->ExercisesGroups as $pos => $group) : ++$pos; ?>
                    <?php if(UserAndExerciseGroups::ExistUserAndGroup($userLesson->id, $group->id)) : ?>
                        <?php $class = $exerciseGroup->id == $group->id ? 'link current' : 'link'; ?>
                        <li><?php echo CHtml::link("$pos.$group->name", array('lessons/pass', 'id'=>$userLesson->id, 'group'=>$group->id), array('class'=>$class)); echo " ($group->nameType)"; ?></li>
                    <?php else : ?>
                            <li><?php echo "$pos.$group->name ($group->nameType)"; ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
            <li>Нет заданий</li>
            <?php endif; ?>
            </ul>
        </div>
        <?php if($exerciseGroup) : ?>
        <h2><?php echo $exerciseGroup->name; ?></h2>
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'exercises-form',
            'enableAjaxValidation'=>false,
        )); ?>
        <?php if($exerciseGroup->type != 2 && $exerciseGroup->Exercises) : ?>
        <?php foreach($exerciseGroup->ExercisesRaw as $posTest => $exercise) : ++$posTest; ?>
        <div class="exercise clearfix">
            <div class="question"><?php echo "$posTest: $exercise->question"; ?></div>
            <div class="answer clearfix" style="display: inline-block;">
                <?php echo CHtml::textField("Exercises[$exercise->id][answer]", '', array('placeholder'=>'Введите ответ', 'class'=>'form-control', 'tabindex'=>$posTest)); ?>
                <div class="result"></div>
                <?php $userExercise = UserAndExercises::model()->findByAttributes(array('id_relation'=>$userAndExerciseGroup->id, 'id_exercise'=>$exercise->id));
                    if($userExercise) echo "<span class='label label-info'>Последний ответ: $userExercise->last_answer</span>";
                ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php elseif($exerciseGroup->type == 2 && $criteriaExercises) : ?>
        <?php foreach($criteriaExercises as $key => $exercise) : ?>
        <div class="exercise clearfix">
            <div class="question"><?php echo $key+1 .": $exercise->question"; ?></div>
            <div class="answer clearfix">
                <?php echo CHtml::textField("Exercises[$key][answer]", '', array('placeholder'=>'Введите ответ', 'class'=>'form-control', 'style'=>'width:100%', 'tabindex'=>$key)); ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php echo CHtml::submitButton('Отправить результаты', array('class'=>'btn btn-success', 'style'=>'margin-top: 20px')); ?>
        <?php else : ?>
        Нет заданий
        <?php endif; ?>
        <?php $this->endWidget(); ?>
        <?php if($exerciseGroup->type != 2) : ?>
            <?php if($userAndExerciseGroup->nextGroup) echo CHtml::link('Перейти к следующей группе заданий<i class="glyphicon glyphicon-arrow-right"></i>', array('lessons/nextgroup', 'id'=>$userAndExerciseGroup->id), array('class'=>'btn btn-success btn-icon-right nextGroup hide', 'style'=>'margin-top: 20px'));
                elseif($userLesson->Lesson->accessNextLesson($userLesson->id)) echo CHtml::link('Следующий урок<i class="glyphicon glyphicon-arrow-right"></i>', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'btn btn-success btn-icon-right nextGroup hide', 'style'=>'margin-top: 20px')); ?>
        <?php endif; ?>
        <?php else : ?>
        Нет группы заданий
        <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-5 col-md-5 lesson-info">
        <div class="widget">
            <div class="theory">
                <h2>Теоретическая часть урока</h2>
                <?php if($exerciseGroup->type != 2) : ?>
                    <?php if(trim($userLesson->Lesson->theory) != '') : ?>
                        <?php echo $userLesson->Lesson->theory; ?>
                    <?php else : ?>
                        Нет теории
                    <?php endif; ?>
                <?php else : ?>
                    Недоступна при прохождении теста
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
