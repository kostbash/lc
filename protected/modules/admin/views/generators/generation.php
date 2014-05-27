<script type='text/javascript'>
    $(function(){
        $('#searchSkill').keyup(function(e){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courseandskills/skillsbyajax', array('id_course'=>$group->id_course, 'with_used'=>false)); ?>',
                type:'POST',
                data: { term: current.val() },
                success: function(result) {
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
        });

        $('#add-skills .dropdown-toggle').click(function(){
            current = $(this);
            
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courseandskills/skillsbyajax', array('id_course'=>$group->id_course, 'with_used'=>false)); ?>',
                type:'POST',
                success: function(result) { 
                    if(result!='') {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result);
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
                    'name' => 'question',
                    'header'=>'Текст задания',
                    'type'=>'raw',
                    'value'=>'$data->question . CHtml::hiddenField("Exercises[$data->number][question]", $data->question, array("id"=>false))',
                    'htmlOptions'=>array('width'=>'30%'),
                ),
                array(
                    'name' => 'correct_answer',
                    'header'=>'Правильный ответ',
                    'type'=>'raw',
                    'value'=>'$data->correct_answer . CHtml::hiddenField("Exercises[$data->number][correct_answer]", $data->correct_answer, array("id"=>false))',
                    'htmlOptions'=>array('width'=>'25%'),
                ),
                array(
                    'name' => 'difficulty',
                    'header'=>'Сложность',
                    'type'=>'raw',
                    'value'=>'CHtml::dropDownList("Exercises[$data->number][difficulty]", $data->difficulty, Exercises::getDataDifficulty(), array("class"=>"form-control", "empty"=>"Выберите сложность"))',
                    'htmlOptions'=>array('width'=>'10%'),
                ),
                array(
                    'name' => 'need_answer',
                    'header'=>'Треб. ответ',
                    'value'=>'CHtml::hiddenField("Exercises[$data->number][need_answer]", 0, array("id"=>false)) . CHtml::checkBox("Exercises[$data->number][need_answer]", $data->need_answer)',
                    'type'=>'raw',
                    'htmlOptions' => array('width' => '10%'),
                ),

                array(
                    'header'=>'Требуемые умения',
                    'type'=>'raw',
                    'value'=>'"<div class=\"skills\" data-id=\"$data->number\"></div>"',
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
                    <div class="skills"></div>
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