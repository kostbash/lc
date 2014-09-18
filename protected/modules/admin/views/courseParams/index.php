<script>
    $(function(){
        $('#subjects-grid .new-record').live('change', function(){
            current = this;
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseParams/createSubject"); ?>',
                type: 'POST',
                data: { CourseSubjects:{ name: $(this).val() } },
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            });
        });

        $('#subjects-grid .update-record').live('change', function(){
            current = this;
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseParams/updateSubject"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            });
        });
        
        $('#subjects-grid .delete').live('click', function() {
            current = $(this);
            if(!confirm('Вы уверены, что хотите удалить данный предмет ?'))
                return false;
            $.ajax({
                url: current.attr('href'),
                type: 'POST',
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            }); 
            return false;
        });
        
        $('#subjects-grid .order .glyphicon-arrow-up').live('click', function() {
            current = $(this).closest('tr');
            up = current.prev('tr');
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseParams/changeOrderSubject"); ?>',
                type:'POST',
                data: {id_current: current.data('id'), id_sibling: up.data('id')},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        current.after(up);
                }
            });
        });
        
        $('#subjects-grid .order .glyphicon-arrow-down').live('click', function() {
            current = $(this).closest('tr');
            down = current.next('tr');
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseParams/changeOrderSubject"); ?>',
                type:'POST',
                data: {id_current: current.data('id'), id_sibling: down.data('id')},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        current.before(down);
                }
            });
        });
        
        $('#classes-grid .new-record').live('change', function(){
            current = this;
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseParams/createClass"); ?>',
                type: 'POST',
                data: { CourseClasses:{ name: $(this).val() } },
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            });
        });

        $('#classes-grid .update-record').live('change', function(){
            current = this;
            $.ajax({
                url:'<?php echo Yii::app()->createUrl("admin/courseParams/updateClass"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            });
        });
        
        $('#classes-grid .delete').live('click', function() {
            current = $(this);
            if(!confirm('Вы уверены, что хотите удалить данный класс ?'))
                return false;
            $.ajax({
                url: current.attr('href'),
                type: 'POST',
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                        $(current).parents('.zgrid').yiiGridView('update');
                    else
                        alert(result.errors);
                }
            }); 
            return false;
        });  
        
        deleteArrows();
    });
    
    function deleteArrows()
    {
        $('#subjects-grid tbody tr:last .order').remove();
    }
    
</script>

<div class="page-header clearfix">
    <h2>Параметры курсов</h2>
</div>
<div class="row">
    
<div class="pull-left col-lg-6 col-md-6">
    <h3 class="head-data">Предметы</h3>
    <div class="well">
        <?php $this->widget('ZGridView', array(
                'id'=>'subjects-grid',
                'dataProvider'=>$subjects->search(),
                'rowHtmlOptionsExpression' => 'array("data-id"=>$data->id)',
                'afterAjaxUpdate'=>"deleteArrows",
                'columns'=>array(
                    array(
                        'header'=>'',
                        'name'=>'order',
                        'type'=>'raw',
                        'value'=>'"<div class=\"order order-rows\">
                                        <i class=\"glyphicon glyphicon-arrow-up\" title=\"переместить вверх\"></i>
                                        <i class=\"glyphicon glyphicon-arrow-down\" title=\"переместить вниз\"></i>
                                    </div>"',
                        'htmlOptions'=>array('width'=>'10%'),
                    ),
                    array(
                        'name'=>'name',
                        'type'=>'textArea',
                        'htmlOptions'=>array('style'=>'width: 30%'),
                    ),

                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{delete}',
                        'buttons'=>array(
                            'delete'=>array(
                                'visible'=>'!$data->isNewRecord',
                                'click'=>'false',
                                'url' => 'Yii::app()->createUrl("admin/courseParams/deleteSubject", array("id"=>$data->id))',
                                'options'=>array('class'=>'delete'),
                            ),
                        ),

                        'htmlOptions'=>array('style'=>'width: 10%'),
                    ),
                ),
        ));?>
    </div>
</div>

<div class="pull-right col-lg-6 col-md-6">
    <h3 class="head-data">Классы</h3>
    <div class="well">
        <?php $this->widget('ZGridView', array(
                'id'=>'classes-grid',
                'dataProvider'=>$classes->search(),
                'columns'=>array(
                    array(
                        'name'=>'name',
                        'type'=>'textArea',
                        'htmlOptions'=>array('style'=>'width: 30%'),
                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{delete}',
                        'buttons'=>array(
                            'delete'=>array(
                                'visible'=>'!$data->isNewRecord',
                                'click'=>'false',
                                'url' => 'Yii::app()->createUrl("admin/courseParams/deleteClass", array("id"=>$data->id))',
                                'options'=>array('class'=>'delete'),
                            ),
                        ),

                        'htmlOptions'=>array('style'=>'width: 10%'),
                    ),
                ),
        ));?>
    </div>
</div>
</div>