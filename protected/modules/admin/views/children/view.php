<script type="text/javascript">
    $(function(){
        $('.filter-exercises').change(function(){
            updateExercisesLogs();
        });
        
        $('.filter-notifications').change(function(){
            updateNotifications();
        });
        
        $('#courses-filter-for-exercises input[name=term]').live('keyup', function(){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courses/coursesbyajax'); ?>',
                type:'POST',
                data: { term: current.val()},
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                    {
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result.html);
                        current.siblings('.input-group-btn').addClass('open');
                    }
                }
            });
        });

        $('#courses-filter-for-exercises .dropdown-toggle').click(function(){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/courses/coursesbyajax'); ?>',
                type:'POST',
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                    {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result.html);
                    }
                }
            });
            
        });

        $('#courses-filter-for-exercises .dropdown-menu li').live('click', function(){
            current = $(this);
            if(current.data('id'))
            {
                term = current.closest('.input-group-btn').siblings('input[name=term]');
                hidden = current.closest('.input-group-btn').siblings('input[type=hidden]');
                term.val(current.find('a').html());
                hidden.val(current.data('id'));
                updateExercisesLogs();
            }   
            current.parents('.input-group-btn').removeClass('open');
            return false;
        });
        
        $('#skills-filter-for-exercises input[name=term]').live('keyup', function(){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/skills/skillsbyajax'); ?>',
                type:'POST',
                data: { term: current.val()},
                success: function(result) { 
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
        });

        $('#skills-filter-for-exercises .dropdown-toggle').click(function(){
            current = $(this);
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('admin/skills/skillsbyajax'); ?>',
                type:'POST',
                success: function(result) {
                    if(result) {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result);
                    }
                }
            });
            
        });

        $('#skills-filter-for-exercises .dropdown-menu li').live('click', function(){
            current = $(this);
            if(current.data('id'))
            {
                term = current.closest('.input-group-btn').siblings('input[name=term]');
                hidden = current.closest('.input-group-btn').siblings('input[type=hidden]');
                term.val(current.find('a').html());
                hidden.val(current.data('id'));
                updateExercisesLogs();
            }   
            current.parents('.input-group-btn').removeClass('open');
            return false;
        });
    });
    function updateExercisesLogs()
    {
        $('#exercises-logs-grid').yiiGridView('update', { data: $('.filter-exercises').serialize()});
    }
    
    function updateNotifications()
    {
        $('#notifications-grid').yiiGridView('update', { data: $('.filter-notifications').serialize()});
    }
</script>
<div class="page-header clearfix">
    <h2>Прогресс ребенка "<?php echo $model->child_name." ".$model->child_surname; ?>"</h2>
</div>
<div class="section">
    <h3 class='head'>Уведомления</h3>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <?php echo CHtml::dropDownList('Notifications[new]', $notifications->new, array(0=>'Только старые', 1=>'Только новые'), array('empty'=>'Отображать старые и новые', 'class'=>'form-control input-sm filter-notifications')); ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-lg-12 col-md-12'>
            <?php
            $this->widget('ZGridView', array(
                'id'=>'notifications-grid',
                'rowHtmlOptionsExpression' => 'array("class"=>"$data->classLog")',
                'summaryText'=>"Новых: ".StudentNotificationsAndTeacher::CountNew(Yii::app()->user->id, $model->id_child),
                'ajaxType'=>'POST',
                'dataProvider'=>$notificationsDataProvider,
                'columns'=>array(
                    'niceDate',
                    'time',
                    'notificationName:raw',
                    'text:raw',
                ),
            ));
            ?>
        </div>
    </div>
</div>
<?php $notifications->madeOldRecord($notificationsDataProvider); ?>

<div class="section">
    <h3 class='head'>Выполненные задания</h3>
    <div class='row'>
        <div class="col-lg-3 col-md-3">
            <?php echo CHtml::dropDownList('ExercisesLogs[new]', $exercisesLogs->new, array(0=>'Только старые', 1=>'Только новые'), array('empty'=>'Отображать старые и новые', 'class'=>'form-control input-sm filter-exercises')); ?>
        </div>
        <div class="col-lg-3 col-md-3">
            <?php echo CHtml::dropDownList('ExercisesLogs[id_block_type]', $exercisesLogs->id_block_type, array(1=>'Только в упражнениях', 2=>'Только в тестах'), array('empty'=>'И в тестах и в упражнениях', 'class'=>'form-control input-sm filter-exercises')); ?>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="input-group mydrop" id="courses-filter-for-exercises">
              <input type="text" name="term" placeholder="Введите название курса" class="form-control input-sm" autocomplete="off" />
              <input type="hidden" name="ExercisesLogs[id_course]" class="filter-exercises" />
              <div class="input-group-btn">
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu"></ul>
              </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="input-group mydrop" id="skills-filter-for-exercises">
              <input type="text" name="term" placeholder="Введите название умения" class="form-control input-sm" autocomplete="off" />
              <input type="hidden" name="ExercisesLogs[id_skill]" class="filter-exercises" />
              <div class="input-group-btn">
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu"></ul>
              </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class='col-lg-12 col-md-12'>
            <?php
            $this->widget('ZGridView', array(
                'id'=>'exercises-logs-grid',
                'rowHtmlOptionsExpression' => 'array("class"=>"$data->classLog")',
                'ajaxType'=>'POST',
                'summaryText'=>"Новых: $exercisesLogs->countNew",
                'dataProvider'=>$exercisesLogsDataProvider,
                'columns'=>array(
                    'niceDate',
                    'time',
                    array(
                        'name'=>'Exercise.Type.name',
                        'header'=>'Тип задания',
                    ),
                    'exerciseName:raw',
                    'rightText',
                    'Exercise.skillsText',
                    array(
                        'name'=>'Exercise.difficulty',
                        'header'=>'Сложность',
                    ),
                    'courseName',
                    'lessonName',
                    'blockName',
                    'duration',
                    'Block.nameType'
                ),
            ));
            ?>
        </div>
    </div>
</div>
<?php $exercisesLogs->madeOldRecord($exercisesLogsDataProvider); ?>