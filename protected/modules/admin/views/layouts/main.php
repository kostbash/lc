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
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <?php echo CHtml::link(Yii::app()->name, array('/courses/list'), array('class'=>'navbar-brand')); ?>
        </div>
        <div class="collapse navbar-collapse">
            <?php
                $this->menu[] = array('label'=>'Курсы', 'url'=>array('/admin/courses/index'));
                $this->menu[] = array('label'=>'Задания', 'url'=>array('/admin/exercises/index'));
                $this->menu[] = array('label'=>'Умения', 'url'=>array('/admin/skills/index'));
                $this->menu[] = array('label'=>'Списки <b class="caret"></b>',
                                      'url'=>array('#'),
                                      'itemOptions'=>array('class'=>'dropdown'),
                                      'submenuOptions'=>array('class'=>'dropdown-menu'),
                                      'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
                                      'items'=>array(
                                            array('label'=>'Слова', 'url'=>array('#')),
                                            array('label'=>'Карты', 'url'=>array('/admin/maps/index')),
                                      ),
                                );
                if(Yii::app()->user->checkAccess('teacher'))
                    $this->menu[] = array('label'=>'Ученики', 'url'=>array('/admin/studentsofteacher/index'));
                if(Yii::app()->user->checkAccess('parent'))
                    $this->menu[] = array('label'=>'Дети', 'url'=>array('/admin/childrenofparent/index'));
                if(Yii::app()->user->checkAccess('admin'))
                {
                    $this->menu[] = array('label'=>'Тексты', 'url'=>array('/admin/sourceMessages/index'));
                    $this->menu[] = array('label'=>'Параметры курсов', 'url'=>array('/admin/courseParams/index'));
                    $this->menu[] = array('label'=>'Пользователи', 'url'=>array('/admin/users/index'));
                }
                
                    $this->widget('zii.widgets.CMenu',array(
                            'items'=>$this->menu,
                            'encodeLabel'=>false,
                            'htmlOptions'=>array('class'=>'nav navbar-nav'),
                    ));
            ?>
            <?php $this->widget('zii.widgets.CMenu',array(
                    'items'=>array(
                            array('label'=>Yii::app()->user->name." (".Users::$rolesRusNames[Users::UserType()].")", 'url'=>array('/admin/users/update'), 'visible'=>!Yii::app()->user->isGuest, 'type'=>'raw'),
                            array('label'=>'Выход', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                    ),
                    'encodeLabel'=>false,
                    'htmlOptions'=>array('class'=>'nav navbar-nav navbar-right'),
            )); ?>
        </div>
    </div>
</div><!-- navbar -->
<div class="container">
<?php if(isset($this->breadcrumbs)):?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
        )); ?><!-- breadcrumbs -->
<?php endif?>

<?php echo $content; ?>
</div>
<div id="footer" class="container bg-main">
        Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
</div><!-- footer -->
</body>
</html>
