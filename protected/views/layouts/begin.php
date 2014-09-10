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
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    <?php echo $content; ?>
</body>
</html>
