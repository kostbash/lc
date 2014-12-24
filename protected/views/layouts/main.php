<!DOCTYPE html>
<html>
<?php
    $messages = SourceMessages::MessagesByCategories('layout-main');
?>    
    
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
                        <div id="slogan"><?php echo Yii::t('layout-main', $messages[1]->message); ?></div>
                    </div>
                    <?php if(!Yii::app()->user->isGuest) : $user = Users::model()->findByPk(Yii::app()->user->id); ?>
                        <div id="achievements-links">
                            <a href="<?php echo $this->createUrl('users/achievements'); ?>" >
                                <div class="top">
                                    <img src="/images/stamina-link.png" height="19" />
                                    <h5><?php echo Yii::t('layout-main', $messages[2]->message); ?></h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->stamina; ?> <span>дней</span>
                                </div>
                            </a>
                            <a href="<?php echo $this->createUrl('users/achievements'); ?>" >
                                <div class="top">
                                    <img src="/images/experience-link.png" height="19" />
                                    <h5><?php echo Yii::t('layout-main', $messages[3]->message); ?></h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->experience; ?> <span>тестов</span>
                                </div>
                            </a>
                            <a href="<?php echo $this->createUrl('users/achievements'); ?>" >
                                <div class="top">
                                    <img src="/images/accuracy-link.png" height="19" />
                                    <h5><?php echo Yii::t('layout-main', $messages[4]->message); ?></h5>
                                </div>
                                <div class="bottom">
                                    <?php echo $user->accuracy; ?> <span>%</span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                        $controller = Yii::app()->getController();
                        $isHome = (($controller->id === Yii::app()->defaultController) && ($controller->action->id === $controller->defaultAction)) ? true : false;
                        if(!$isHome)
                        {
                            $this->renderPartial('//site/reg_login', array('user'=>new Users, 'login'=>new LoginForm));
                        }
                    ?>
                    <div id="login">
                        <div id="logining" class="clearfix">
                            <?php if(Yii::app()->user->isGuest) : ?>
                                <div class="reg-as-student" data-toggle="modal" data-target="#regLogin"><a href="#" onclick="reachGoal('RegisterStart')"><?php echo Yii::t('layout-main', $messages[24]->message); ?></a></div>
                                <div class="login-button orange-button" data-toggle="modal" data-target="#regLogin"><a href="#" onclick="reachGoal('HomeLoginStart')"><?php echo Yii::t('layout-main', $messages[23]->message); ?></a></div>
                            <?php else : ?>
                                <div id='logout' class="orange-button"><?php echo CHtml::link('Выход', array('site/logout')); ?></div>
                                <?php
                                    $userPageLink = $user->username." (".Users::$rolesRusNames[Users::UserType()].")";
                                    if($user->role==2)
                                    {
                                        $userPageLink .= "<br /><span style='font-size: 14px;'>Родитель: ";
                                        if($user->ParentRelation)
                                        {
                                            $userPageLink .= $user->ParentRelation->Parent->email;
                                        }
                                        else
                                        {
                                            $userPageLink .= "не зарегистрирован";
                                        }
                                        $userPageLink .= '</span>';
                                    }
                                ?>
                                <div id='user-page-link'><?php echo CHtml::link($userPageLink, array('/users/update')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div id="top-menu-on-main" class="clearfix" >
                            <ul class="clearfix">
                                <?php if(Yii::app()->user->isGuest) : ?>
                                    <?php /*
                                    <li><a class="reg-as-parent" data-toggle="modal" data-target="#regLogin" href="#"><?php echo Yii::t('layout-main', $messages[25]->message); ?></a></li>
                                    <li><a class="reg-as-teacher" data-toggle="modal" data-target="#regLogin" href="#"><?php echo Yii::t('layout-main', $messages[26]->message); ?></a></li>
                                    <li><a id="contacts" href="#"><?php echo Yii::t('layout-main', $messages[27]->message); ?></a></li>
                                     
                                     */ ?>
                                    
                                    <li><?php echo CHtml::link(Yii::t('layout-main', $messages[25]->message), array('/site/page', 'view'=>'for-parents')); ?></li>
                                    <li><?php echo CHtml::link(Yii::t('layout-main', $messages[26]->message), array('/site/page', 'view'=>'for-teachers')); ?></li>
                                    <li><?php echo CHtml::link(Yii::t('layout-main', $messages[27]->message), array('site/contact')); ?></li>
                                <?php else : ?>
                                    <li><?php echo CHtml::link(Yii::t('layout-main', $messages[28]->message), array('site/contact')); ?></li>
                                    <li><?php echo CHtml::link(Yii::t('layout-main', $messages[29]->message), array('/site/page', 'view'=>'for-students')); ?></li>
                                    <li><?php echo CHtml::link(Yii::t('layout-main', $messages[30]->message), array('/site/page', 'view'=>'about')); ?></li>
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
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            <?php if(!YII_DEBUG) { ?>
            var yaParams = {
                login: '<?php echo ((!Yii::app()->user->isGuest)?Yii::app()->user->name:'Guest')?>'
                <?php echo isset($this->Course)&&$this->Course?(", course: '".$this->Course."'"):"" ?>
                <?php echo isset($this->Lesson)&&$this->Lesson?(",lesson: '".$this->Lesson."'"):"" ?>
                <?php echo isset($this->Block)&&$this->Block?(",block: '".$this->Block."'"):"" ?>
            };
            (function (d, w, c) 
            { 
                (w[c] = w[c] || []).push(function() { 
                    try { 
                        w.yaCounter26929302 = new Ya.Metrika({id:26929302, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true, params:window.yaParams}); 
                    } catch(e) { } }); 
                    var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; 
                    s.type = "text/javascript"; 
                    s.async = true; 
                    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; 
                    if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");
            <?php } ?>    
            function reachGoal(goal)
            {
                <?php if(!YII_DEBUG) { ?>
                window.yaCounter26929302.reachGoal(goal);
                <?php } ?> 
            }     
            $(window).on('load',function(){ 
            <?php
                if(isset($_SESSION['goals'])&&  is_array($_SESSION['goals']) && count($_SESSION['goals']))
                {
                    foreach($_SESSION['goals'] as $goal)
                    {
                        echo "reachGoal('$goal');";
                    }
                    unset($_SESSION['goals']);
                }
            ?>});
        </script>
        <?php if(!YII_DEBUG) { ?>
        <noscript>
            <div>
                <img src="//mc.yandex.ru/watch/26929302" style="position:absolute; left:-9999px;" alt="" />
            </div>
        </noscript>
        <?php } ?> 
        <!-- /Yandex.Metrika counter -->
</body>
</html>