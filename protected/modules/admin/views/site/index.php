<?php $this->pageTitle=Yii::app()->name." - Обучающие курсы для школьников"; ?>

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

<h1>Добро пожаловать на сайт <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<div>Описание целевых пользователей, решаемых задачи преимуществ программы (для детей)</div>
<?php $this->renderPartial('login', array('model'=>$loginForm)); ?>
<?php $this->renderPartial('registration', array('model'=>$user)); ?>
<?php echo CHtml::button('Зарегистрироваться', array('class'=>'btn btn-info btn-lg', 'data-toggle'=>"modal", 'data-target'=>"#regModel")); ?> или
<?php echo CHtml::button('Войдите', array('class'=>'btn btn-default', 'data-toggle'=>"modal", 'data-target'=>"#loginForm")); ?>
