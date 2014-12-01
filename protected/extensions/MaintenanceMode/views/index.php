<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="ru" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div id="site-closed">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3>Сайт временно не работает !</h3>
        </div>
        <div class="panel-body">
            <?php echo Yii::app()->maintenanceMode->message; ?>
        </div>
    </div>
</div>

    
</body>
</html>