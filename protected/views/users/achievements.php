<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.zclip.min.js"); ?>
    <div id="separate-header-part">
        <img src="/images/separate-two-part.png" width="1026" height="14" />
    </div>
    <div id="back-header-bottom">
        <div id="header-bottom">
            <div id="head-full-column" class="head-column">
                <div class="content-mini">
                    <div class="head">Ваши достижения</div>
                    <div class="foot">На этой странице отображаются все ваши успехи.</div>
                </div>
            </div>
        </div>        
    </div>
</div><!-- end-header-->

<script>
    $(function(){
        $('.name p').popover();
        $('#bottom .next-button').zclip({
            path: '/js/ZeroClipboard.swf',
            copy: function(){
                return $(this).attr('href');
            },
            afterCopy: function(){
                alert('Ссылка скопирована в Ваш буфер обмена');
            }
        });
    });
</script>

<?php
    $placeStamina = $user->placeByAttribute('stamina');
    $placeExp = $user->placeByAttribute('experience');
    $placeAccuracy = $user->placeByAttribute('accuracy');
    $placeWisdom = $user->placeByAttribute('wisdom');
?>

<div id="container">
    <div id="achievements-page">
        <table id="achievements-table">
            <tr class="odd">
                <td class="icon"><img src="/images/achivments-stamina.png" width="47" height="47" /></td>
                <td class="name">
                    <p data-toggle="popover" data-trigger="hover" data-title="Подсказка" data-container="#achievements-page" data-placement="right" data-content="Сколько дней подряд Вы учитесь в системе">
                        Выносливость
                    </p>
                </td>
                <td class="value"><?php echo $user->stamina; ?> дней</td>
                <td class="place <?php echo Users::$places[$placeStamina]; ?>">
                    <?php echo $placeStamina; ?> место
                </td>
                <td class="medal">
                    <?php if(Users::$placeImage[$placeStamina]) : ?>
                    <img src="/images/<?php echo Users::$placeImage[$placeStamina]?>" width="54" height="72" />
                    <?php endif; ?>
                </td>
            </tr>
            
            <tr class="even">
                <td class="icon"><img src="/images/achivments-exp.png" width="47" height="47" /></td>
                <td class="name">
                    <p data-toggle="popover" data-trigger="hover" data-title="Подсказка" data-container="#achievements-page" data-placement="right" data-content="Число тестов, которое Вы успешно прошли">
                        Опыт
                    </p>
                </td>
                <td class="value"><?php echo $user->experience; ?> тестов</td>
                <td class="place <?php echo Users::$places[$placeExp]; ?>">
                    <?php echo $placeExp; ?> место
                </td>
                <td class="medal">
                    <?php if(Users::$placeImage[$placeExp]) : ?>
                    <img src="/images/<?php echo Users::$placeImage[$placeExp]?>" width="54" height="72" />
                    <?php endif; ?>
                </td>
            </tr>
            
            <tr class="odd">
                <td class="icon"><img src="/images/achivments-accuracy.png" width="47" height="47" /></td>
                <td class="name">
                    <p data-toggle="popover" data-trigger="hover" data-title="Подсказка" data-container="#achievements-page" data-placement="right" data-content="Средний бал по всем тестам">
                        Точность
                    </p>
                </td>
                <td class="value"><?php echo $user->accuracy; ?>% <span>из 100%</span></td>
                <td class="place <?php echo Users::$places[$placeAccuracy]; ?>">
                    <?php echo $placeAccuracy; ?> место
                </td>
                <td class="medal">
                    <?php if(Users::$placeImage[$placeAccuracy]) : ?>
                    <img src="/images/<?php echo Users::$placeImage[$placeAccuracy]?>" width="54" height="72" />
                    <?php endif; ?>
                </td>
            </tr>
            
            <tr class="even">
                <td class="icon"><img src="/images/achivments-wisdom.png" width="47" height="47" /></td>
                <td class="name">
                    <p data-toggle="popover" data-trigger="hover" data-title="Подсказка" data-container="#achievements-page" data-placement="right" data-content="Число навыков, которыми Вы овладели">
                        Мудрость
                    <p>
                </td>
                <td class="value"><?php echo $user->wisdom; ?></td>
                <td class="place <?php echo Users::$places[$placeWisdom]; ?>">
                    <?php echo $placeWisdom; ?> место
                </td>
                <td class="medal">
                    <?php if(Users::$placeImage[$placeWisdom]) : ?>
                    <img src="/images/<?php echo Users::$placeImage[$placeWisdom]?>" width="54" height="72" />
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php if(Yii::app()->user->id == $user->id) : ?>
            <div id="bottom">
                <?php echo CHtml::link('Скопировать ссылку на достижения', array('users/achievements', 'key'=>$user->progressKey), array('class'=>'next-button')); ?>
            </div>
        <?php endif; ?>
    </div>
</div>