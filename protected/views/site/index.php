<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php if($showRegModal) : ?>
<script type="text/javascript">
    $(function() {
        $('#regModel').removeClass('fade').modal('show');
    });
</script>
<?php endif; ?>
<?php if($showLoginModal) : ?>
<script type="text/javascript">
    $(function() {
        $('#loginForm').removeClass('fade').modal('show');
    });
</script>
<?php endif; ?>

<script type="text/javascript">
    $(function(){
        $('#student').click(function(){
            $('#role').val(2);
        });
        
        $('#teacher').click(function(){
            $('#role').val(3);
        });
        
        $('#parent').click(function(){
            $('#role').val(4);
        });
    });
</script>

<div id="first-page" class="clearfix">
    <div class="clearfix">
        <div id="header" class="pull-left">
            <h1>Курс Обучения сложению в пределах 100</h1>
            <h2>за 34 урока по 15 минут</h2>
        </div>
        <div class="pull-right">
            <div><?php echo CHtml::link("Войдите <i class='glyphicon glyphicon-log-in' style='top: 2px'></i>", '#', array('id'=>'login-open', 'data-toggle'=>"modal", 'data-target'=>"#loginForm")); ?></div>
            <?php $this->renderPartial('login', array('model'=>$loginForm)); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7 col-md-7">
            <div class="list">
                <h2>Как работает курс:</h2>
                <ul>
                    <li><i class="glyphicon glyphicon-pencil" style="padding-right: 7px"></i>Ребенок может проходить уроки самостоятельно</li>
                    <li><i class="glyphicon glyphicon-pencil" style="padding-right: 7px"></i><i>Понятно:</i> упражнения для усвоения материала</li>
                    <li><i class="glyphicon glyphicon-pencil" style="padding-right: 7px"></i><i>Твердо:</i> большое количество тестов для отработки и контроля навыков сложения</li>
                </ul>
            </div>
            <div class="list">
                <h2>Что нужно чтобы начать:</h2>
                <ul>
                    <li><i class="glyphicon glyphicon-pencil" style="padding-right: 7px"></i>Знать цифры</li>
                    <li><i class="glyphicon glyphicon-pencil" style="padding-right: 7px"></i>Уметь считать до 100</li>
                    <li><i class="glyphicon glyphicon-pencil" style="padding-right: 7px"></i>Иметь начальные навыки сложения до 10</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-5 col-md-5">
            <div style="text-align: center; margin-top: 58px">
                <?php $this->renderPartial('registration', array('model'=>$user)); ?>
                <?php echo CHtml::button('Начните обучение', array('class'=>'btn btn-info btn-lg', 'data-toggle'=>"modal", 'data-target'=>"#regModel", 'id'=>'student')); ?>
                <div style="font-size: 20px; color: #666; margin: 13px 0;">ИЛИ</div>
                <?php echo CHtml::link('Проверьте знания ребенка', array('lessons/check'), array('class'=>'btn btn-success btn-lg')); ?>
                <?php echo CHtml::link('Зарегистрироваться как <b>Педагог</b>', '#', array('class'=>'btn btn-link', 'data-toggle'=>"modal", 'data-target'=>"#regModel", 'id'=>'teacher')); ?>
                <?php echo CHtml::link('Зарегистрироваться как <b>Родитель</b>', '#', array('class'=>'btn btn-link', 'data-toggle'=>"modal", 'data-target'=>"#regModel", 'id'=>'parent')); ?>
            </div>
        </div>
    </div>
</div>
