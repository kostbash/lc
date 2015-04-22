<?php
Yii::app()->clientScript->registerScript("UpdateByAjax",
    "$(function(){

            $('.zgrid .new-record').live('change', function(){
                current = this;
                $.ajax({
                    'url':'".Yii::app()->createUrl("admin/skillsgroups/create")."',
                    'type':'POST',
                    'data': {'Groups':{'name':$(this).val(), id_course:". $id_course ."}},
                    'success': function(result) {
                                    if(result==1)
                                        $(current).parents('.zgrid').yiiGridView('update');
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });

            $('.zgrid .update-record').live('change', function(){
                current = this;
                $.ajax({
                    'url':'".Yii::app()->createUrl("admin/skillsgroups/update")."',
                    'type':'POST',
                    'data':$(this).serialize(),
                    'success': function(result) {
                                    if(result==1)
                                        $(current).parents('.zgrid').yiiGridView('update');
                                    else if(result!='')
                                        alert(result);
                     }
                });
            });



            $('.delete-main-skill').live('click', function() {
                current = $(this);
                if(current.closest('tr').data('candelete') === 1)
                    if(!confirm('Вы уверены, что хотите удалить данное умение ?'))
                        return false;
                      $.ajax({
                         'url': current.attr('href'),
                         'type':'POST',
                         'success': function(result) {
                                         if(result==1)
                                             current.parents('.zgrid').yiiGridView('update');
                                         else if(result!=='')
                                             alert(result);
                          }
                     });
                return false;
            });

            $('.input-mini-container .close').live('click', function() {
                current = $(this);
                if(!confirm('Вы уверены, что хотите удалить данное умение ?'))
                    return false;
                $.ajax({
                   'url': current.attr('href'),
                   'type':'POST',
                   'success': function(result) {
                                   if(result==1)
                                       current.closest('.zgrid').yiiGridView('update');
                                   else if(result!=='')
                                       alert(result);
                    }
                });
                return false;
             });
        });"
);
?>



<div class="page-header clearfix">
    <h2>Группы умений курса <?php if($course) echo " \"$course->name\""; ?></h2>
<!--    <a class="btn btn-success btn-sm" href="/admin/skills/showtree/id_course/--><?//=$course->id?><!--">Посмотреть дерево умений</a>-->
<!--    <a style="margin-right: 10px;" class="btn btn-success btn-sm" href="/admin/skillsgroups/index/id_course/--><?//=$course->id?><!--">Группы умений</a>-->
</div>

<div class="row">
    <div class="pull-left col-lg-5 col-md-6">
        <h3 class="head-data">Группы умений</h3>
        <div class="well">

<?php $this->widget('ZGridView', array(
	'dataProvider'=>$model->searchGroups(),
    'id'=>'knowledge-grid',
    'rowHtmlOptionsExpression' => 'array("data-candelete"=>$data->canDelete)',
    'columns'=>array(

        array(
            'name'=>'name',
            //'header'=>'N1',
            'type'=>'textAreaSkill',
            'htmlOptions'=>array('style'=>'width: 90%'),
        ),

        array(
            'class'=>'CButtonColumn',
            'template'=>'{delete}',
            'buttons'=>array(
                'delete'=>array(
                    'visible'=>'!$data->isNewRecord',
                    'click'=>'false',
                    'options'=>array('class'=>'delete-main-skill'),
                ),
            ),

            'htmlOptions'=>array('style'=>'width: 10%'),
        ),
    ),

)); ?>
        </div>
    </div>
</div>
</div>