<script type="text/javascript">
    $(function() {
        $('#lessons-grid .delete').live('click', function() {
            current = $(this);
            if(current.closest('tr').data('candelete') === 1)
            {
                if(confirm('Вы уверены, что хотите удалить данный урок ?')) {
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
                }
            } else {
                alert('Урок используется в курсах удаление невозможно');
            }
            return false;
        });
    });
</script>
<div class="page-header clearfix">
    <h2>Уроки</h2>
    <?php echo CHtml::link('<i class="glyphicon glyphicon-plus"></i>Добавить', array('/admin/lessons/create'), array('class'=>'btn btn-primary btn-icon')); ?>
</div>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'lessons-form',
    'enableAjaxValidation' => false,
	));
?>
<?php $this->widget('ZGridView', array(
	'id'=>'lessons-grid',
    	'selectableRows' => 2,
	'dataProvider'=>$model->search(),
        'rowHtmlOptionsExpression'=> 'array("data-candelete"=>"$data->canDelete")',
	'columns'=>array(
            array(
                    'class'=>'CCheckBoxColumn',
                    'id' => 'checked',
		    'value' => '$data->id',
                    'visible'=>$idLessonGroup,
            ),
            'name',
            'coursesString',
            'skillsString',
            array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}{delete}',
                    'buttons'=>array(
                        'delete' => array(
                            'options'=>array('class'=>'delete'),
                            'click'=>'false',
                        ),
                    ),
                    'htmlOptions'=>array('style'=>'width: 10%'),
            ),
	),
)); ?>
<?php if($idLessonGroup) echo CHtml::submitButton("Выбрать",array('class'=>'btn btn-default')); ?>
<?php $this->endWidget(); ?>