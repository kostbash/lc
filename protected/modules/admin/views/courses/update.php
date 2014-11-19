<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js");
Yii::app()->clientScript->registerScript('#courses', "
    var skill_number=1;
    var globalTimeout = null;  
    var current = null;
    var ee = null;
    
    function SkillsByAjax()
    {
    if(ee.keyCode!=13){
            $.ajax({
                'url':'".Yii::app()->createUrl('admin/courseandskills/skillsbyajax', array('id_course'=>$model->id))."',
                'type':'POST',
                'data': { term: current.val() },
                'success': function(result) { 
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
            }
    }

    $(function(){
        $('#courseSkill input').live('keyup', function(e){
        if(globalTimeout != null) clearTimeout(globalTimeout);  
        globalTimeout =setTimeout(SkillsByAjax,200);  
            current = $(this);
            ee = e;
        });
        
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

        $('#courseSkill .dropdown-toggle').live('click', function(){
            current = $(this);
            
            $.ajax({
                'url':'".Yii::app()->createUrl('admin/courseandskills/skillsbyajax', array('id_course'=>$model->id))."',
                'type':'POST',
                'success': function(result) { 
                    if(result!='') {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result);
                    }
                }
            });
            
        });

        $('#courseSkill .dropdown-menu li').live('click', function(){
            current = $(this);
            dataId = current.data('id');
            nameSkill = current.find('a').html();
            
            $.ajax({
                url:'".Yii::app()->createUrl('admin/courseandskills/create')."',
                type:'POST',
                data: { id_course:$model->id, id_skill:dataId },
                dataType: 'json',
                success: function(result) {
                    if(result.success) {
                        if(dataId) {
                            $('#newskills').append('<tr>'+result.html+'</tr>');
                            $('#skills-row').append(result.html);
                            //$('#skills-row').find('span').text('');
                            //$('#skills-row').find('span').text('');
                            dropSkills();
                        }
                        current.parents('.input-group-btn').removeClass('open');
                    } else {
                        alert(result.html);
                    }
                }
            });

            return false; 
        });
        
        $('.skill-course a').live('click', function(){ 
            current = $(this);
            if(confirm('Вы уверены, что хотите удалить умение ?'))
            {
                $.ajax({
                    url: current.attr('href'),
                    type:'POST',
                    dataType: 'json',
                    success: function(result) {
                        if(result.success)
                        {
                            if(result.type=='removefromcourse')
                            {
                                 $('#skills-row').find('[data-id='+current.closest('.skill-course').data('id')+']').remove();
                            
                            }
                            else
                            {
                                $('.block[data-id='+current.closest('.skill-course').data('block')+'] .block-skills select[data-id='+current.closest('.skill-course').data('id')+']').remove();
                                //current.closest('.skill-course').data('block')
                            current.closest('.skill-course').remove();
                            }
                           
                            
                            makeSkills();
                        }
                    }
                });
            }
            return false;
        });
        
        $('.block input, .block select').live('change', function() {
            current = $(this);
            if(current.is('[name*=type]')) {
                if(!confirm('Все задания группы удаляться, продолжить ?'))
                    return false;
            }
            $.ajax({
                url:'".Yii::app()->createUrl('admin/groupofexercises/updatebyajax')."',
                type:'POST',
                data: current.serialize(),
                dataType: 'json',
                success: function(result) { 
                    if(result.success) {
                        if(result.needUpdate) {}
                            
                    }
                }
            });
        });
        
        $('.theme-name textarea[name=name]').live('change', function() {
            current = $(this);
            $.ajax({
                url:'".Yii::app()->createUrl('admin/groupoflessons/changename')."',
                type:'POST',
                data: { name: current.val(), id_group: current.closest('.theme').data('id') }
            });
        });
        
        $('.lesson-name textarea').live('change', function() {
            current = $(this);
            if(current.val())
            {
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/lessons/changename')."',
                    type:'POST',
                    data: current.serialize()
                });
             } else {
                alert('Введите название');
             }
        });
        
        $('#add-theme a').click(function(){
            current = $(this);
            input = current.siblings('input[name=name]');
            name = $.trim(input.val());
            if(name) {
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/groupoflessons/create', array('id_course'=>$model->id))."',
                    type:'POST',
                    data: { name: input.val() },
                    dataType: 'json',
                    success: function(result) {
                        if(result.success)
                        {
                            lastTheme = $('#course-themes .theme').last();
                            if(lastTheme.length)
                            {
                                lastTheme.after(result.html);
                            } else {
                                $('#skills').after(result.html);
                            }
                            input.val('');
                            sortLessons();
                            makeSkills();
                        }
                    }
                });
            } else {
                alert('Введите название группы');
                input.focus();
            }
            return false;
        });
        
        $('#add-lesson a').click(function(){
            current = $(this);
            input = current.siblings('input[name=name]');
            name = $.trim(input.val());
            if(name) {
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/lessons/createincourse', array('id_course'=>$model->id))."',
                    type:'POST',
                    data: { name: input.val() },
                    dataType: 'json',
                    success: function(result) {
                        if(result.success)
                        {
                            $('#lessons-course').find('.blocks-container').append(result.html.exerciseGroupHtml); //result.html.lessonHtml
                            $('#lessons-course').find('.lessons-container > table > tbody').append(result.html.lessonHtml);
                            setHeightLessons();
                            sortLessons();
                            makeSkills();
                            sortBlocks();
                            input.val('');
                        }
                    }
                });
            } else {
                alert('Введите название урока');
                input.focus();
            }
            return false;
        });
        
        $('#add-block a').click(function(){
            current = $(this);
            input = current.siblings('input[name=name]');
            type = current.siblings('select');
            name = $.trim(input.val());
            if(name) {
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/groupofexercises/createincourse', array('id_course'=>$model->id))."',
                    type:'POST',
                    data: { name: input.val(), type: type.val() },
                    dataType: 'json',
                    success: function(result) {
                        if(result.success)
                        {
                            $('#blocks-course .blocks > tbody').append(result.html);
                            input.val('');
                            sortBlocks();
                            dropSkills();
                        }
                    }
                });
            } else {
                alert('Введите название блока');
                input.focus();
            }
            return false;
        });
        
        $('.block .block-operation .remove').live('click', function() {
            current = $(this);
            if(confirm('Вы уверены, что хотите удалить группу заданий ?'))
            {
                $.ajax({
                    url: current.attr('href'),
                    type:'POST',
                    success: function(result) {
                        if(result == 1)
                        {
                            current.closest('.block').remove();
                            setHeightLessons();
                            makeSkills();
                        }
                    }
                });
            }
            return false;
        });
                
        $('.lesson .lesson-remove a').live('click', function() {
            current = $(this);
            if(confirm('Вы уверены, что хотите удалить урок ?'))
            {
                id_lesson = current.closest('.lesson').data('id');
                $.ajax({
                    url: '".Yii::app()->createUrl('admin/lessons/delete')."',
                    type:'POST',
                    data: {id_course: ".$model->id.", id_lesson: id_lesson },
                    success: function(result) {
                        if(result == 1)
                        {
                            lesson = current.closest('.lesson');
                            blocks = lesson.closest('.lessons-container').siblings('.blocks-container').find('.blocks[data-id='+lesson.data('id')+'] .block');
                            $('#blocks-course .blocks-container > table > tbody').append(blocks);
                            $('.blocks[data-id='+lesson.data('id')+']').remove();
                            lesson.remove();
                            makeSkills();
                        } else {
                            alert(result);
                        }
                    }
                });
            }
            return false;
        });
        
        $('.theme .theme-name a').live('click', function() {
            current = $(this);
            if(confirm('Вы уверены, что хотите удалить группу уроков ?'))
            {
                id_theme = current.closest('.theme').data('id');
                $.ajax({
                    url: '".Yii::app()->createUrl('admin/groupoflessons/delete')."',
                    type:'POST',
                    data: {id_course: ".$model->id.", id_theme: id_theme },
                    success: function(result) {
                        if(result == 1)
                        {
                            lessons = current.closest('.theme-name').siblings('.lessons-container').find('.lesson');
                            blocks = current.closest('.theme-name').siblings('.blocks-container').find('.blocks');
                            $('#lessons-course .blocks-container').append(blocks);
                            $('#lessons-course .lessons-container > table > tbody').append(lessons);
                            current.closest('.theme').remove();
                            makeSkills();
                        } else {
                            alert(result);
                        }
                    }
                });
            }
            return false;
        });
    });
    
    function calcLessonsHeight(pos, lessonItem)
        {
            lesson = $(lessonItem);
            blocks = lesson.closest('.lessons-container').siblings('.blocks-container').find('.blocks[data-id='+lesson.data('id')+']');
            lesson.css('height', '');
            blocks.css('height', '');
            //alert(blocks.outerHeight() +' - '+ lesson.outerHeight());
            //alert(blocks.height() +' - '+ lesson.height());
            if(blocks.outerHeight() > lesson.outerHeight()+1)
            {
                if($.browser.mozilla)
                {
                    height = pos==0 || pos==1 ? blocks.height() : blocks.height()+1;
                }
                else
                {
                    height = pos==0 || pos==1 ? blocks.height()-1 : blocks.height();
                }
                
                lesson.height(height);
            }
        }

    function setHeightLessons() {
        $('.lesson').each(calcLessonsHeight);
        
       
    }
    
    setHeightLessons();
    
    function dropSkills()
    {
        $('.block').droppable({
            accept: '.skill-course',
            tolerance: 'pointer',
            hoverClass: 'droppable-hover',
            
            drop: function(event,info) {
                skill = $(info.draggable);
                blockSkills = $(this);
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/groupofexercises/addskill')."',
                    type:'POST',
                    data: { id_group: blockSkills.closest('.block').data('id'), id_skill:skill.data('id') },
                    dataType: 'json',
                    success: function(result) {
                        if(result.success) {
                            blockSkills.closest('.block').replaceWith(result.html);
                            //dropSkills();
                            setHeightLessons();
                            makeSkills();
                        } else {
                            alert(result.message);
                        }
                    }
                });
                //makeSkills();
            },
            
            activate: function() {
                //$(this).css('background', '#eee');
            },
            
            deactivate: function() {
                //$(this).css('background', '#fff');
            }
        });
        
        $('.skill-course').draggable({
            cursor: 'move',
            helper: 'clone',
            containment: 'window',
            opacity: 0.8,
            revert: true
        });
    }
    
    dropSkills();
        
    function sortBlocks()
    {
        $('.blocks > tbody').sortable({
            delay:0,
            axis: 'y',
            connectWith: '.blocks > tbody',
            cursor: 'move',
            update: function(event, ui) {
                current = $(this);
                blocks = $(this).closest('.blocks');
                positions = new Array();
                current.find('tr').each(function(pos, group) {
                    positions[pos] = $(group).data('id');
                });
                
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/lessons/changepositions')."',
                    type:'POST',
                    data: { id_lesson: blocks.data('id'), id_course: blocks.data('idcourse'), positions: positions },
                    dataType: 'json',
                    success: function(result) {
                        if(result.success)
                        {
                            setHeightLessons();
                            makeSkills();
                        }
                    }
                });
                
            },
            
            activate: function(event, ui) {
                $('.blocks > tbody').css('background', '#FFE000');
            },
            
            deactivate: function(event, ui) {
                $('.blocks > tbody').css('background', '');
            }
        });
    }
    sortBlocks();
    
    function sortLessons()
    {
        receive = 0;
        $('.lessons-container > table > tbody').sortable({
            axis: 'y',
            connectWith: '.lessons-container > table > tbody',
            cursor: 'move',
            update: function(event, ui) {
                positions = new Array();
                itemId = ui.item.data('id');
                $(this).find('.lesson').each(function(pos, lesson) {
                    lessId = $(lesson).data('id');
                    positions[pos] = lessId;
                });
                if(receive==0)
                {
                    index = ui.item.index() + 1;
                    blocks = ui.item.closest('.lessons-container').siblings('.blocks-container').find('.blocks[data-id='+ itemId +']');
                    aa = ui.item.closest('.lessons-container').siblings('.blocks-container').find('.blocks:nth-child('+ index +')');
                    if(blocks.index()+1 > index) {
                        aa.before(blocks);
                    } else {
                        aa.after(blocks);
                    }
                } else {
                    receive = 0;
                }
                $.ajax({
                    url:'".Yii::app()->createUrl('admin/groupoflessons/changepositions')."',
                    type:'POST',
                    data: { id_theme: $(this).closest('.theme').data('id'), id_course: $('#course').data('id'), positions: positions },
                    dataType: 'json',
                    success: function(result) {
                       if(result.success)
                       {
                          
                       } else {
                           
                       }
                    }
                });
                makeSkills();
            },

            receive: function(event, ui) {
                blocks = ui.sender.closest('.lessons-container').siblings('.blocks-container').find('.blocks[data-id='+ ui.item.data('id') +']');
                index = ui.item.index() + 1;
                aa = ui.item.closest('.lessons-container').siblings('.blocks-container').find('.blocks:nth-child('+ index +')');
                if(aa.length) {
                    aa.before(blocks);
                } else {
                    ui.item.closest('.lessons-container').siblings('.blocks-container').append(blocks);
                }
                receive = 1;
            },
             
            activate: function(event, ui) {
                $('.lessons-container > table > tbody').css('background', '#008FD6');
            },

            deactivate: function(event, ui) {
                $('.lessons-container > table > tbody').css('background', '');
            }
         });
         
    }
    sortLessons();
    
function setSelectColors(p,b)
{
$('.block select[data-id='+$(b).data('id')+']').css('background-color', $('.skill-course[data-id='+$(b).data('id')+']').css('background-color'));
}

    function makeSkills()
    {
    var has_skills = new Array();
    $('.skills-row').remove();
    
    $('#skills-row').find('.skill-course').each(function(p,b){
                id = $(b).data('id');
                has_skills[id]=0;
                $('.block select[data-id='+id+']').css('background-color', $('.skill-course[data-id='+id+']').css('background-color'));
        });
    
        $('#skills-row').css('display', 'none').width('100%').find('.skill-course').css('width','');;

        $('.block').each(function(pos, block) {
            
            row = $('<div>').addClass('skills-row');
            $('#skills-table').append(row);
            row.css({
                'top': $(block).position().top-row.parent().position().top+'px',
                'height': $(block).height()+'px',
                'display': ''
            });
            
            $(block).children('.block-skills').children('table').children('tbody').children('tr').children('td').children('select').each(
                function(p, b)
                {
                    id = $(b).data('id');
                    block_id = $(block).data('id');
                    has_skills[id]++;
                
                    skill = $('#skills-row div[data-id='+id+']').clone();
                    skill.css('display','');
                    skill.attr('data-block', block_id);
                    skill.find('a.skill-remove-icon').attr('href', '/admin/groupofexercises/removeskillbygroup/id_group/'+block_id+'/id_skill/'+id);
                    row.append(skill);
                }
            );
            
            row.find('.skill-course:visible').css('width',(100/row.find('.skill-course:visible').length)+'%');
        });
            $('#skills-row').css('display', 'none');
        setNewSkills(has_skills);
        dropSkills();
    }
    function setNewSkills(has_skills)
    {
        $('#newskills tr').remove(); //alert(has_skills);
        $('#skills-row').find('.skill-course').each(function(p,b){
                if(has_skills[$(b).data('id')]==0)
                {
                sk = $(b).clone();
                    sk.width('100%');
                    sk.find('a.skill-remove-icon').attr('href', '/admin/courseandskills/delete/id_skill/'+$(b).data('id')+'/id_course/'+'".$model->id."');
                    tr = $('<div>'); 
                tr.append(sk);
                tr.addClass('skills-row');
                    $('#newskills').append(tr);
                    }
            }
            );
    }


    makeSkills();
    
    $('#searchSkill').keyup(function(e){

        if(e.keyCode==13){
                $.ajax({
                    'url':'".Yii::app()->createUrl('/admin/skills/create')."',
                    'type':'POST',
                    'data': {'Skills':{'name':$(this).val(), 'type':2, 'fromcourse':1, id_course:$model->id}}, //2=навык
                    'success': function(result) { 
                                    if(result!='')
                                    {
                                    $('#courseSkill .input-group-btn').removeClass('open');
                                    $('#searchSkill').val('');
                                        $.ajax({
                                            url:'".Yii::app()->createUrl('admin/courseandskills/create')."',
                    type:'POST',
                                            data: { id_course:$model->id, id_skill:result, title: 1 },
                    dataType: 'json',
                    success: function(result) {
                                            
                        if(result.success) {
                            skill_number++;
                                                    //if(dataId) 
                                                    //{
                                                        $('#newskills').append('<div class=skills-row>'+result.html+'</div>');
                                                        $('#skills-row').append(result.html);
                                                        //$('#skills-row').find('span').text('');
                            dropSkills();
                                                    //}
                                                    
            } else {
                                                    alert(result.html);
            }
                                            } //success: function(result) {
                                        }); //$.ajax({
                                    } //if(result!='')
                                    else if(result!='')
                                        alert(result);
                     } //'success': function(result) {
                }); //$.ajax({
//            } else {
//                alert('Введите название умения');
//                current.focus();
//            }
        } //if(e.keyCode==13){
    }); //keyup

");
?>

<div class="page-header clearfix">
    <div class="row">
        <div class="col-lg-7 col-md-7">
             <h2>Редактирование курса: "<?php echo $model->name; ?>"</h2>   
        </div>
        <div class="col-lg-5 col-md-5">
            <div class="export-button">
                <button type="button" class="dropdown-toggle" data-toggle="dropdown">Экспорт курса<span class="caret"></span></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="<?php echo Yii::app()->createUrl('courses/print', array('id'=>$model->id, 'with_right'=>0)); ?>" target="_blank">Печать</a></li>
                    <li><a href="<?php echo Yii::app()->createUrl('courses/toPdf', array('id'=>$model->id, 'with_right'=>0)); ?>" target="_blank">PDF</a></li>
                </ul>
                <input id='with-right' type='checkbox' class='with-right' name value='0' />
                <label for='with-right'>С ответами</label>
            </div>
            <?php echo CHtml::link('<i class="glyphicon glyphicon-ok"></i>Сохранить', '#', array('class'=>'btn btn-success btn-icon', 'onclick'=>"$('#courses-form').submit(); return false;")); ?>
        </div>        
    </div>
</div>

<div class="form" id="course" data-id="<?php echo $model->id; ?>">

<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'courses-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="section main-attrs">
    <h3 class="head">Основное</h3>
    <div class="row">
        <div class="col-lg-2 col-md-2"><?php echo $form->labelEx($model,'name'); ?></div>
        <div class="col-lg-6 col-md-6">
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control', 'placeholder'=>'Введите название курса')); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
        <div class="col-lg-4 col-md-4">
            <?php echo CHtml::link('Умения курса', array('/admin/skills/index', 'id_course'=>$model->id), array('class'=>'btn btn-success btn-sm')); ?>
            <?php echo CHtml::link('<i class="glyphicon glyphicon-cog"></i>Параметры курса', array('/admin/courses/params', 'id_course'=>$model->id), array('class'=>'btn btn-primary btn-sm btn-icon')); ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
    
<table id="course-themes" class="clearfix" style="width: 100%">
        <colgroup style=''>
            <col style='width: 15%;'>
            <col style='width: 55%;'>
            <col style='width: 15%;'>
            <col style='width: 15%;'>
        </colgroup>
    <thead>
        <tr>
            <th>Умения</th>
            <th>Блоки</th>
            <th>Уроки</th>
            <th>Темы</th>
        </tr>
    </thead>
    <tbody>
        <tr id="skills">
            <td rowspan='1000' style="vertical-align: top;">
                <div id="skills-table">
                    <div id="skills-row" style="display:none;">
                        <?php foreach($model->Skills as $nSkill => $courseSkill) : ?>
                        <?php
                            Skills::$number[$courseSkill->id] = $nSkill;

                            if($courseSkill->hasBlocks($model->id))
                                echo $courseSkill->htmlForCourse($model->id, $nSkill);
                        ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php foreach($model->LessonsGroups as $theme) : ?>
            <?php echo $theme->getHtmlForCourse(true); ?>
        <?php endforeach; ?>

        <?php
            $modelExerciseGroupsHtml = '';
            $modelLessonsHtml = '';
            foreach($model->Lessons as $lessonCourse)
            {
                $html = $lessonCourse->getHtmlForCourse();
                $modelExerciseGroupsHtml .= $html['exerciseGroupHtml'];
                $modelLessonsHtml .= $lessonCourse->htmlForCourse['lessonHtml'];
            }

         ?>
        <tr id='lessons-course' data-idcourse="<?php echo $model->id; ?>">
            <td class='blocks-container active-blocks' style="vertical-align: top;"><?php echo $modelExerciseGroupsHtml;  ?></td>
            <td class='lessons-container' style="vertical-align: top;"><?php if(true || $modelLessonsHtml) { ?><table><tbody><?php echo $modelLessonsHtml;  ?></tbody></table><?php } ?></td>
        </tr>
            <?php  ?>
        <tr id='blocks-course'>
            <td class='blocks-container'  style="vertical-align: top;">
                <table class='blocks' data-idcourse="<?php echo $model->id; ?>"><tbody>
                    <?php foreach($model->Blocks as $blockCourse) : ?>
                        <?php echo $blockCourse->htmlForCourse; ?>
                    <?php endforeach; ?>
                </tbody></table>
            </td>
        </tr>

    </tbody>
    <tfoot>
         <tr>
            <td>
                <div id="newskills">
                    <?php foreach($model->Skills as $nSkill => $courseSkill) : ?>
                        <?php
                            //Skills::$number[$courseSkill->id] = $nSkill;
                            if(!$courseSkill->hasBlocks($model->id))
                                echo $courseSkill->htmlForCourse($model->id, $nSkill, true);
                        ?>
                        <?php endforeach; ?>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
<div id="additional" class="clearfix">
    <div id="select-skills">
        <div class="input-group mydrop" id="courseSkill">
            <?php echo CHtml::textField("Skills[name]", '', array('placeholder'=>'Введите название умения', 'class'=>'form-control input-sm', 'id'=>'searchSkill')); ?>
            <div class="input-group-btn">
                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                </ul>
            </div>
        </div>
    </div>
    <div id="add-block" class="clearfix">
        <?php echo CHtml::textField('name', "", array('id'=>false, 'class'=>'form-control input-sm', 'placeholder'=>'Введите название блока')); ?>
        <?php echo CHtml::dropDownList("type", "", GroupOfExercises::$typeGroup, array('class'=>'form-control input-sm')); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>', "#", array('id'=>false, 'class'=>'btn btn-success btn-sm')); ?>
    </div>
    <div id="add-lesson" class="clearfix">
        <?php echo CHtml::textField('name', "", array('id'=>false, 'class'=>'form-control input-sm', 'placeholder'=>'Введите название урока')); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>', "#", array('id'=>false, 'class'=>'btn btn-success btn-sm')); ?>
    </div>
    </td>
    <div id="add-theme" class="clearfix">
        <?php echo CHtml::textField('name', "", array('id'=>false, 'class'=>'form-control input-sm', 'placeholder'=>'Введите название группы уроков')); ?>
        <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>', "#", array('id'=>false, 'class'=>'btn btn-success btn-sm')); ?>
    </div>
</div>

</div><!-- form -->