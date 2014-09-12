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
    <div id="wrapper">
        <div id="header">
            <div id="back-header-top">
                <div id="header-top">
                    <div id="logo">
                        <a href="<?php echo Users::getLogoLink(); ?>"><?php echo Yii::app()->name; ?></a>
                        <div id="slogan">Обучающие курсы для школьников</div>
                    </div>
                    <?php if(!Yii::app()->user->isGuest) : $user = Users::model()->findByPk(Yii::app()->user->id); ?>
                        <div id="achievements-links">
                            <a href="<?php echo $this->createUrl('users/progress'); ?>" >
                                <div class="top">
                                    <img src="/images/stamina-link.png" height="19" />
                                    <h5>Выносливость</h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->stamina; ?> <span>дней</span>
                                </div>
                            </a>
                            <a href="<?php echo $this->createUrl('users/progress'); ?>" >
                                <div class="top">
                                    <img src="/images/experience-link.png" height="19" />
                                    <h5>Опыт</h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->experience; ?> <span>тестов</span>
                                </div>
                            </a>
                            <a href="<?php echo $this->createUrl('users/progress'); ?>" >
                                <div class="top">
                                    <img src="/images/accuracy-link.png" height="19" />
                                    <h5>Точность</h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->accuracy; ?> <span>%</span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div id="login">
                        <div id="logining" class="clearfix">
                            <?php if(Yii::app()->user->isGuest) : ?>
                                <div id="reg-as-student" data-toggle="modal" data-target="#regModel"><a href="#">Зарегистрируйтесь</a></div>
                                <div class="login-button orange-button" data-toggle="modal" data-target="#loginForm"><a href="#">Войдите</a></div>
                            <?php else : ?>
                                <div id='logout' class="orange-button"><?php echo CHtml::link('Выход', array('site/logout')); ?></div>
                                <div id='user-page-link'><?php echo CHtml::link(Yii::app()->user->name." (".Users::$rolesRusNames[Users::UserType()].")", array('/users/update')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div id="top-menu-on-main" class="clearfix" >
                            <ul class="clearfix">
                                <?php if(Yii::app()->user->isGuest) : ?>
                                    <li><a id="reg-as-parent" data-toggle="modal" data-target="#regModel" href="#">РОДИТЕЛЯМ</a></li>
                                    <li><a id="reg-as-teacher" data-toggle="modal" data-target="#regModel" href="#">ПЕДАГОГАМ</a></li>
                                    <li><a id="contacts" href="#">КОНТАКТЫ</a></li>
                                <?php else : ?>
                                    <li><?php echo CHtml::link('ЕСТЬ ВОПРОС ?', array('site/contact')); ?></li>
                                    <li><?php echo CHtml::link('УЧЕНИКАМ', '#'); ?></li>
                                    <li><?php echo CHtml::link('О ПРОЕКТЕ', array('/site/page', 'view'=>'about')); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>        
            </div>
        <?php echo $content; ?>
    </div>
    <div id="back-footer">
        <div id="footer">
            <div id="copyright">
                &copy; <?php echo date('Y'); ?> "<?php echo Yii::app()->name; ?>" <br />
                Все права защищены
            </div>
        </div>
    </div>
</body>
</html>