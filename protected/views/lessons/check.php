<?php 
    $this->pageTitle="Вступительный тест к <$course->name>. ".Yii::app()->name.".";
    $this->renderPartial("_pass_scripts");
    $this->renderPartial("blocks/_2_scripts");
?>
    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Проверьте насколько уверенно ваш ребенок складывает и считает</div>
                    <div class="foot">Проверка насколько ваш ребенок уверенно складывает и считает позволяет заранее узнать о его умственных способностяхи подготовиться к разного рода сюрпризам.</div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<div id="container">
    <div id="check-page">
        <div id="lesson-page">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'exercises-form',
                'enableAjaxValidation'=>false,
            )); ?>
            <h2 class="block-name">ЗАПИШИТЕ ОТВЕТ В ПОЛЯ СПРАВА ОТ ВОПРОСОВ</h2>
            <?php if($exercises) : $position=0; ?>
                <div id="exercises">
                    <?php foreach($exercises as $index => $exercise) : $position++; ?>
                        <div id="exercise_<?php echo $index; ?>" class="exercise <?php echo (++$i%2)==0 ? 'even' : 'odd'; ?>">
                            <div class="head clearfix">
                                <div class="number"><?php echo ++$number; ?></div>
                                <div class="condition"><?php echo "$exercise->condition"; ?></div>
                            </div>
                            <div class="answer clearfix">
                                <?php if($exercise->id_visual) : ?>
                                    <?php $this->renderPartial("/exercises/visualizations/{$exercise->id_visual}", array('model'=>$exercise, 'key'=>$exercise->id, 'index'=>$index+1)); ?>
                                <?php endif; ?>
                            </div>
                            <input class="duration" type="hidden" name="Exercises[<?php echo $key; ?>][duration]" value="0" />
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                Нет заданий
                <input type="hidden" name="Exercises" />
            <?php endif; ?>

            <div id="bottom">
                <?php if($nextGroup) : ?>
                    <?php echo CHtml::link('Далее', '#', array('class'=>'next-button', 'tabindex'=>$key+2));?>
                    <div id="leftStep"><?php echo "Вам осталось <b>$leftStep</b> шага"; ?></div>
                <?php else : ?>
                    <?php echo CHtml::link('Просмотреть результат', '#', array('class'=>'next-button', 'tabindex'=>$key+2));?>
                <?php endif; ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
