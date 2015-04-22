<?php
$this->renderPartial("_pass_scripts", array('user'=>$user));
$this->pageTitle="$title";
?>

<?if (isset($_SESSION['error_'.$course_id])):?>
    <script>
        $(document).ready(function(){
            alert('<?=$_SESSION['error_'.$course_id]?>');
            <? unset($_SESSION['error_'.$course_id])?>
        });
    </script>
<?endif?>
<div class="music" style="display: none"></div>
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
<!--                            --><?php
//                            $contentSkills .= "<table>";
//                            if($userLesson->Lesson->Skills) {
//                                $numSkill = 1;
//                                foreach($userLesson->Lesson->Skills as $lessonSkill)
//                                {
//                                    $contentSkills .= "<tr class='skill'>";
//                                    $contentSkills .= "<td class='number'>$numSkill</td>";
//                                    $contentSkills .= "<td class='name'>$lessonSkill->name</td>";
//                                    $contentSkills .= "</tr>";
//                                    ++$numSkill;
//                                }
//                            }
//                            else
//                            {
//                                $contentSkills .= "<tr class='skill no-skills'><td class='number'></td><td class='name'>Нет умений<td></tr>";
//                            }
//
//                            $contentSkills .= "</table>";
//                            ?>
<!--                            <div id="lesson-name">-->
<!--                                --><?php //echo "УРОК {$userLesson->Lesson->position}: ".$userLesson->Lesson->name; ?>
<!--                                <div id="skills" data-toggle="popover" data-trigger ="hover" data-html='true' data-container="#lesson-name" data-placement="right" data-content="--><?php //echo $contentSkills; ?><!--"></div>-->
<!--                            </div>-->
<!--                            --><?php //echo CHtml::link('Курсы:', array('courses/list'), array('style'=>'color:#263870;')); ?>
<!--                            --><?php //echo CHtml::link($userLesson->Course->name, array('courses/index', 'id'=>$userLesson->Course->id), array('id'=>'course-name')); ?>
<!--                        </div>-->
                        <div id='buttons'>
<!--                            --><?php
//                            if( $userLesson->Lesson->accessNextLesson($userLesson->id) )
//                                echo CHtml::link('Следующий урок', array('courses/nextlesson', 'id_user_lesson'=>$userLesson->id), array('class'=>'next-lesson-button'));
//                            ?>
<!--                            <div class="export-button">-->
<!--                                <button type="button" class="dropdown-toggle" data-toggle="dropdown">Экспорт текущего блока<span class="caret"></span></button>-->
<!--                                <ul class="dropdown-menu pull-right" role="menu">-->
<!--                                    <li><a href="--><?php //echo Yii::app()->createUrl('lessons/printBlock', array('block'=>$exerciseGroup->id, 'with_right'=>0)); ?><!--" target="_blank">Печать</a></li>-->
<!--                                    <li><a href="--><?php //echo Yii::app()->createUrl('lessons/blockToPdf', array('block'=>$exerciseGroup->id, 'with_right'=>0)); ?><!--" target="_blank">PDF</a></li>-->
<!--                                </ul>-->
<!--                                --><?php //if(Yii::app()->user->checkAccess('editor')) : ?>
<!--                                    <input id='with-right' type='checkbox' class='with-right' name value='0' />-->
<!--                                    <label for='with-right'>С ответами</label>-->
<!--                                --><?php //endif; ?>
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                    <div id="blocks">
                        <h1>Блоки урока</h1>
                        <ul>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div></div></div>

    <div id="container">
        <div id="lesson-page">

            <h2 class="block-name">Блок "<?=$block['title']?>"</h2>
            <?php if($block['tasks']):?>
                <?php
                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'exercises-form',
                    'enableAjaxValidation'=>false,
                ));
                ?>
                <input type="hidden" name="Exercises" />
                    <?php
                    $this->renderPartial("blocks/new_$block_type", array(
                        'tasks'=>$block['tasks'],
                        'course_id' => $course_id,
                    ));
                    ?>
                    <?php $this->endWidget(); ?>
            <?php else: ?>
                <?php if ($block['null_block']):?>
                    Нет блоков
                <?php else: ?>
                    Нет уроков
                <?php endif?>
            <?php endif?>
        </div>
    </div>

