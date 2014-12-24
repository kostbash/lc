<script type='text/javascript'>
    $(function(){
        $('#searchSkill').keyup(function(e){
        e.preventDefault();
        current = $(this);
        if(e.keyCode==13){
            current = $(this);
            name = $.trim(current.val());
            if(name) {
                $.ajax({
                    url:'<?php echo Yii::app()->createUrl('admin/skills/addcourseskill', array('id_course'=>$group->id_course))?>',
                    type:'POST',
                    data: current.serialize(),
                    dataType: 'json',
                    success: function(result) {
                        if(result.success) {
                            id = result.id;
                            
                           if(id)
                            {
                                name = result.name;
                                skillsContainer = current.closest('#add-skills').find('.skills');
                                skillExist = skillsContainer.find('.skill[data-id='+id+']');
                                if(!skillExist.length)
                                    skillsContainer.append(getSkills(id, name));
                            }
                            current.siblings('.input-group-btn').removeClass('open');
                            current.val('');
                        }
                    }
                });
            }
        }
        else
        {
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courseandskills/skillsbyajax', array('id_course'=>$group->id_course, 'with_used'=>false)); ?>',
                type:'POST',
                data: { term: current.val(), firstIds: [<?php echo implode(',', $group->IdsUsedSkills); ?>] },
                dataType: 'json',
                success: function(result) {
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result.html);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
            }
            return false;
        }).keydown(function( event ) {
  if ( event.which == 13 ) {
    event.preventDefault();
  }
});

        $('#add-skills .dropdown-toggle').click(function(e){
            current = $(this);

            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courseandskills/skillsbyajax', array('id_course'=>$group->id_course, 'with_used'=>false)); ?>',
                type:'POST',
                dataType: 'json',
                data: { firstIds: [<?php echo implode(',', $group->IdsUsedSkills); ?>] },
                success: function(result) { 
                    if(result) {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result.html);
                    }
                }
            });
        
        });

        $('#add-skills .dropdown-menu li').live('click', function(){
            current = $(this);
            id = current.data('id');
            if(id)
            {
                name = current.find('a').html();
                skillsContainer = current.closest('#add-skills').find('.skills');
                skillExist = skillsContainer.find('.skill[data-id='+id+']');
                if(!skillExist.length)
                    skillsContainer.append(getSkills(id, name));
            }
            current.closest('.input-group-btn').removeClass('open');
            return false;
        });
        
        $('.skill .remove').live('click', function(){
            $(this).closest('.skill').remove();
        });
        
        $('#insert-skills').click(function(){
            checked = $('.select-on-check:checked');
            skills = $('#add-skills .skills .skill');
            if(checked.length && skills.length)
            {
                checked.each(function(n, check)
                {
                    check = $(check);
                    skillsContainer = check.closest('tr').find('.skills');
                    skills.each(function(k, skill)
                    {
                        skill = $(skill);
                        skill_id = skill.data('id');
                        skill_name = skill.find('.name').html();
                        skillExist = skillsContainer.find('.skill[data-id='+skill_id+']');
                        if(!skillExist.length)
                            skillsContainer.append(getSkills(skill_id, skill_name, check.val()));
                    });
                });
            }
            return false;
        });
        
        $('#insert-difficulty').click(function(){
            insertVal = $('#add-difficulty select').val();
            checked = $('.select-on-check:checked');
            if(checked.length)
            {
                checked.each(function(n, check)
                {
                    check = $(check);
                    select = check.closest('tr').find('select[name*=difficulty]');
                    select.val(insertVal);
                });
            }
            return false;
        });
        
    });
    
    function getSkills(id, name, number)
    {
        result = '<div class="skill clearfix" data-id='+id+'>';
            result += '<p class="name">'+name+'</p>';
            result += '<p class="remove">&times;</p>';
            if(number)
                result += '<input type="hidden" value="'+id+'" name="Exercises['+number+'][SkillsIds][]" />';
        result += '</div>';
        return result;
    }
</script>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'template-form',
	'enableAjaxValidation'=>false,
)); ?>

<div id='result-generation'>
    <div class="page-header clearfix">
        <h2 style="width: 60%; margin-right: 1%; border-bottom: none;">Настройки заданий</h2>
        <?php if($exercises) echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить задания', '#', array('class'=>'btn btn-success btn-icon btn-sm', 'onclick'=>"$('#template-form').submit(); return false;", 'style'=>'float:right; margin-left: 1%; width: 14%;')); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-repeat"></i>Еще раз', array('/admin/generators/settings', 'id'=>$generator->id,'id_group'=>$group->id), array('class'=>'btn btn-success btn-icon btn-sm', 'style'=>'float:right; margin-left: 1%; width: 11%;', /*'onclick'=>'location.reload(); return false;'*/)); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-remove"></i>Отмена', array('/admin/groupofexercises/update', 'id'=>$group->id), array('class'=>'btn btn-danger btn-icon btn-sm', 'style'=>'float:right; width: 11%;')); ?>
    </div>

    <div class="section">
        <div id="exercises">
        <?php $this->widget('ZGridView', array(
            'id'=>"exercises-grid",
            'summaryText'=>'<div style="text-align:right; font-weight: bold; margin: 10px 0;">Всего заданий: {count}</div>',
            'htmlOptions' => array('class'=>'clearfix zgrid'),
            'dataProvider'=>new CArrayDataProvider($exercises, array('pagination'=>false)),
            'selectableRows'=>2,
            'columns'=>array(
                array(
                    'class' => 'CCheckBoxColumn',
                    'id' => 'checked',
                    'value'=>'$data->number',
                    'htmlOptions'=>array('width'=>'10%'),
                ),
                array(
                    'name' => 'condition',
                    'header'=>'Текст задания',
                    'type'=>'raw',
                    'value'=>'$data->condition . CHtml::hiddenField("Exercises[$data->number][condition]", $data->condition, array("id"=>false))',
                    'htmlOptions'=>array('width'=>'55%'),
                ),
                array(
                    'name' => 'difficulty',
                    'header'=>'Сложность',
                    'type'=>'raw',
                    'value'=>'CHtml::dropDownList("Exercises[$data->number][difficulty]", $data->difficulty, Exercises::getDataDifficulty(), array("class"=>"form-control", "empty"=>"Выберите сложность"))',
                    'htmlOptions'=>array('width'=>'10%'),
                ),
                
                array(
                    'header'=>'Требуемые умения',
                    'type'=>'raw',
                    'value'=>'"<div class=\"skills-mini\"><div class=\"skills\" data-id=\"$data->number\"></div></div>"',
                    'htmlOptions'=>array('width'=>'15%'),
                ),
            ),
        )); ?>
        </div>
    </div>
    <?php if($exercises) : ?>
    <div class="section">
        <div class="row">
            <div class="col-lg-2 col-md2">
                Для выделенных задать умения
            </div>
            <div class="col-lg-4 col-md-4">
                <div id="add-skills">
                    <div class="input-group mydrop ">
                        <?php echo CHtml::textField("Skills[name]", '', array('placeholder'=>'Введите название умения', 'class'=>'form-control input-sm', 'id'=>'searchSkill', 'autocomplete'=>'off')); ?>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                            </ul>
                        </div>
                    </div>
                    <div class="skills-mini">
                        <div class="skills"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md2">
                <a id="insert-skills" href="#" class="btn btn-success btn-icon btn-sm"><i class="glyphicon glyphicon-plus"></i>Задать умения</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md2">
                Для выделенных задать сложность
            </div>
            <div class="col-lg-4 col-md-4">
                <div id="add-difficulty">
                    <?php echo CHtml::dropDownList("difficulty", '', Exercises::getDataDifficulty(), array("class"=>"form-control input-sm", "empty"=>"Выберите сложность")) ?>
                </div>
            </div>
            <div class="col-lg-2 col-md2">
                <a id="insert-difficulty" href="#" class="btn btn-success btn-icon btn-sm"><i class="glyphicon glyphicon-plus"></i>Задать сложность</a>
            </div>
        </div>
        <?php 
            echo CHtml::hiddenField('Template[id_type]', $visual->id_type);
            echo CHtml::hiddenField('Template[id_visual]', $visual->id);
        ?>
        <div id="answersHiddens">
            <?php
                if(!empty($answers)) // выводим все сгенерированные неправильные ответы.
                {
                    foreach($answers as $indexExercise => $answer)
                    {
                        foreach($answer as $indexAnswer => $attrs)
                        {
                            echo CHtml::hiddenField("Exercises[$indexExercise][answers][$indexAnswer][answer]", $attrs['answer']);
                            if($attrs['is_right'])
                                echo CHtml::hiddenField("Exercises[$indexExercise][answers][$indexAnswer][is_right]", 1);
                        }
                    }
                }
            ?>
        </div>
        
        <div id="comparisonsHiddens">
            <?php
                if(!empty($comparisons)) // выводим все сопоставления
                {
                    foreach($comparisons as $indexExercise => $comparison)
                    {
                        foreach($comparison as $indexComparison => $attrs)
                        {
                            foreach($attrs as $attrName => $attr)
                            {
                                echo CHtml::hiddenField("Exercises[$indexExercise][comparisons][$indexComparison][$attrName]", $attr);
                            }
                        }
                    }
                }
            ?>
        </div>
    </div>
    <?php endif; ?>
    <?php
        if($generator->Template->number_exercises != $count)
        {
           echo "<div class='alert alert-danger'>
                    <p><b>Не удается сгенерировать нужное кол-во заданий. ПРОВЕРЬТЕ УСЛОВИЯ</b></p>
                    <p>Надо: {$generator->Template->number_exercises}</p>
                    <p>Сгенерировано: $count</p>
                    <p>Попыток: $attempts</p>
                </div>";
        }
    ?>
</div>
<?php $this->endWidget(); ?>