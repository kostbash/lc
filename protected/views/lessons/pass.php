<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js"); ?>
<script type="text/javascript">
    
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

    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content">
                    <div id="lesson-page-header">
                        <div class="clearfix">
                            <div id="lesson-info">
                                <?php
                                    $contentSkills .= "<table>";
                                        if($userLesson->Lesson->Skills) {
                                            $numSkill = 1;
                                            foreach($userLesson->Lesson->Skills as $lessonSkill)
                                            {
                                                $contentSkills .= "<tr class='skill'>";
                                                    $contentSkills .= "<td class='number'>$numSkill</td>";
                                                    $contentSkills .= "<td class='name'>$lessonSkill->name</td>";
                                                $contentSkills .= "</tr>";
                                                ++$numSkill;
                                            }
                                        } else {
                                            $contentSkills = "<tr><td><td>Нет умений<td></tr>";
                                        }
                                    $contentSkills .= "</table>";
                                ?>
                                <div id="lesson-name">
                                    <?php echo "УРОК $userLesson->position: ".$userLesson->Lesson->name; ?>
                                    <div id="skills" data-toggle="popover" data-trigger ="hover" data-html='true' data-container="#lesson-name" data-placement="right" data-content="<?php echo $contentSkills; ?>"></div>
                                </div>
                                <?php echo CHtml::link('Название курса "'.$userLesson->Course->name.'"', array('courses/index', 'id'=>$userLesson->Course->id), array('id'=>'course-name')); ?>
                            </div>
                            <div id="next-lesson">
                                <?php 
                                    if( $userLesson->Lesson->accessNextLesson($userLesson->id) )
                                        echo CHtml::link('Следующий урок', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'next-lesson-button'));
                                ?>
                            </div>
                        </div>
                        <div id="blocks">
                            <h1>Блоки урока</h1>
                            <ul>
                                <?php if($userLesson->Lesson->ExercisesGroups) : ?>
                                    <?php foreach($userLesson->Lesson->ExercisesGroups as $pos => $group) : ++$pos; ?>
                                        <?php if(UserAndExerciseGroups::ExistUserAndGroup($userLesson->id, $group->id)) : ?>
                                            <?php
                                                if($exerciseGroup->id == $group->id)
                                                {
                                                    $class = 'current';
                                                    $currentPos = $pos;
                                                } else {
                                                    $class = '';
                                                }
                                            ?>
                                            <li>
                                                <?php
                                                    echo "$pos. ";
                                                    echo CHtml::link($group->name, array('lessons/pass', 'id'=>$userLesson->id, 'group'=>$group->id), array('class'=>$class));
                                                    if($group->type==2) echo '<span class="test">Тест!</span>'; 
                                                ?>
                                            </li>
                                        <?php else : ?>
                                            <li>
                                                <?php
                                                    echo "$pos. $group->name";
                                                    if($group->type==2) echo '<span class="test">Тест!</span>'; 
                                                ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li>Нет блоков</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="lesson-page">
        <?php if($exerciseGroup) : ?>
            <h2 class="block-name"><?php echo "Блок $currentPos: \"$exerciseGroup->name\""; ?></h2>
            <?php
            $form=$this->beginWidget('CActiveForm', array(
                'id'=>'exercises-form',
                'enableAjaxValidation'=>false,
            ));
            ?>
                <input type="hidden" name="Exercises" />
                <?php
                    $this->renderPartial("blocks/{$exerciseGroup->type}", array(
                        'userLesson'=>$userLesson,
                        'userAndExerciseGroup'=>$userAndExerciseGroup,
                        'exerciseGroup'=>$exerciseGroup,
                        'exercisesTest' => $exercisesTest,
                    ));
                ?>
            <?php $this->endWidget(); ?>
        <?php else : ?>
            Не существует группы заданий
        <?php endif; ?>
    </div>
</div>
