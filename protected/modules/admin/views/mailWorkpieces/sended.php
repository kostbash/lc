<div class="page-header clearfix">
    <h2>Лента</h2>
</div>

<div class="btn-group">
  <?php echo CHtml::link('Заготовки', array('/admin/mailWorkpieces/unsended'), array('class'=>'btn btn-default')); ?>
  <?php echo CHtml::link('Отправленные', array('/admin/mailWorkpieces/sended'), array('class'=>'btn btn-default active')); ?>
</div>

<?php
$this->widget('ZGridView', array(
    'id'=>'mail-workpieces-grid',
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name'=>'id',
            'htmlOptions'=>array('style'=>'width: 5%;'),
        ),
        array(
            'name'=>'addressee',
            'htmlOptions'=>array('style'=>'width: 10%;'),
        ),
        array(
            'header'=>'Пользователь',
            'value'=>'$data->User->username',
            'htmlOptions'=>array('style'=>'width: 10%;'),
        ),
        array(
            'name'=>'ruleName',
            'htmlOptions'=>array('style'=>'width: 16%;'),
        ),
        array(
            'name'=>'number',
            'htmlOptions'=>array('style'=>'width: 5%;'),
        ),
        array(
            'name'=>'subject',
            'htmlOptions'=>array('style'=>'width: 14%;'),
        ),
        array(
            'name'=>'template',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'width: 40%;'),
        ),
    ),
));
?>
