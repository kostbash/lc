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
                            <a href="<?php echo $this->createUrl('users/achievements'); ?>" >
                                <div class="top">
                                    <img src="/images/stamina-link.png" height="19" />
                                    <h5>Выносливость</h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->stamina; ?> <span>дней</span>
                                </div>
                            </a>
                            <a href="<?php echo $this->createUrl('users/achievements'); ?>" >
                                <div class="top">
                                    <img src="/images/experience-link.png" height="19" />
                                    <h5>Опыт</h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->experience; ?> <span>тестов</span>
                                </div>
                            </a>
                            <a href="<?php echo $this->createUrl('users/achievements'); ?>" >
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
                                <?php
                                    $controller = Yii::app()->getController();
                                    $isHome = (($controller->id === Yii::app()->defaultController) && ($controller->action->id === $controller->defaultAction)) ? true : false;
                                    if(!$isHome)
                                    {
                                        $this->renderPartial('//site/login', array('model'=>new LoginForm));
                                        $this->renderPartial('//site/registration', array('model'=>new Users));
                                    }
                                ?>
                                <script type="text/javascript">
                                    $(function(){
                                        $('#reg-as-student').click(function(){
                                            $('#user-role-student').attr('checked', 'checked');
                                            $('#reg-form input[type=submit]').val('Зарегистрироваться');
                                        });

                                        $('#reg-as-teacher').click(function(){
                                            $('#user-role-teacher').attr('checked', 'checked');
                                            $('#reg-form input[type=submit]').val('Зарегистрироваться');
                                        });

                                        $('#reg-as-parent').click(function(){
                                            $('#user-role-parent').attr('checked', 'checked');
                                            $('#reg-form input[type=submit]').val('Зарегистрироваться');
                                        });
                                    });
                                </script>
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
                <br />
                <!-- Yandex.Metrika informer --><a href="https://metrika.yandex.ru/stat/?id=26929302&amp;from=informer" target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/26929302/3_1_FFFFFFFF_EFEFEFFF_0_pageviews" style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:26929302,lang:'ru'});return false}catch(e){}"/></a><!-- /Yandex.Metrika informer -->
            </div>
        </div>
    </div>
    <!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter26929302 = new Ya.Metrika({id:26929302, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/26929302" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
</body>
</html>