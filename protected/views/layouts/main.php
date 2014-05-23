<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/bootstrap.min.js"); ?>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
        <link href='http://fonts.googleapis.com/css?family=Bad+Script|Lobster&subset=cyrillic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.splitter.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="bg-main-back">
<div class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <?php 
                if(Users::UserType() == 1)
                    echo CHtml::link(Yii::app()->name, array('admin/courses/index'), array('class'=>'navbar-brand', 'id'=>'menu-logo'));
                else
                    echo CHtml::link(Yii::app()->name, array('/courses/index','id'=>Courses::$defaultCourse), array('class'=>'navbar-brand', 'id'=>'menu-logo'));
            ?>
        </div>
        <div class="collapse navbar-collapse">
            <?php 
//                $this->widget('zii.widgets.CMenu',array(
//                        'items'=>array(
//                                array('label'=>'Курсы', 'url'=>array('/courses/list')),
//                                array('label'=>'Мои курсы', 'url'=>array('/courses/mylist')),
//                        ),
//                        'htmlOptions'=>array('class'=>'nav navbar-nav'),
//                ));
                if(Yii::app()->user->id)
                {
                    $user = Users::model()->findByPk(Yii::app()->user->id);
                }
            ?>
            <?php $this->widget('zii.widgets.CMenu',array(
                    'items'=>array(
                            array('label'=>"Выносливость: $user->stamina дней<br>Опыт: $user->experience тестов<br>Точность: $user->accuracy%", 'itemOptions'=>array('id'=>'myprogress'), 'url'=>array('/users/progress'), 'visible'=>!Yii::app()->user->isGuest),
                            array('label'=>'Профиль', 'url'=>array('/users/update'), 'visible'=>!Yii::app()->user->isGuest), //Yii::app()->user->name
                            array('label'=>'Обратная связь', 'url'=>array('/site/contact'), 'visible'=>!Yii::app()->user->isGuest),
                            array('label'=>'О системе', 'url'=>array('/site/page', 'view'=>'about'), 'visible'=>!Yii::app()->user->isGuest),
                            array('label'=>'Выход', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                    ),
                    'encodeLabel'=>false,
                    'htmlOptions'=>array('class'=>'nav navbar-nav navbar-right', 'id'=>'main-menu'),
            )); ?>
        </div>
    </div>
</div><!-- navbar -->
<div class="container bg-main">
<?php if(isset($this->breadcrumbs)):?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
                'homeLink'=>false,
        )); ?><!-- breadcrumbs -->
<?php endif?>

<?php echo $content; ?>
</div>
<div id="footer" class="container bg-main">
        &copy; <?php echo date('Y'); ?>
</div><!-- footer -->
</body>
</html>
