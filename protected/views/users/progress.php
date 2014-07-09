<div id="ochivki" class="widget" style="padding-top: 10px">
    <div class="page-header clearfix">
        <h2><?php echo $progress_key ? "Достижения пользователя: $user->email" : 'Ваши достижения'; ?></h2>
    </div>
    <table id="progress">
        <tr>
            <td class="name">Выносливость</td>
            <td class="desc">Это сколько дней подряд Вы учитесь в системе</td>
            <td class="value"><?php echo $user->stamina; ?> дней</td>
            <td class="place">
                <?php echo $placeStamina = $user->placeByAttribute('stamina');?> место
                <?php if(Users::$placeImage[$placeStamina]) : ?>
                    <div class="medal"><img src="/images/<?php echo Users::$placeImage[$placeStamina]?>" /></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="name">Опыт</td>
            <td class="desc">Число тестов, которое Вы успешно прошли</td>
            <td class="value"><?php echo $user->experience; ?> тестов</td>
            <td class="place">
                <?php echo $placeExp = $user->placeByAttribute('experience');?> место
                <?php if(Users::$placeImage[$placeExp]) : ?>
                    <div class="medal"><img src="/images/<?php echo Users::$placeImage[$placeExp]?>" /></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="name">Точность</td>
            <td class="desc">Средний бал по всем тестам</div>
            <td class="value"><?php echo $user->accuracy; ?>%</div>
            <td class="place">
                <?php echo $placeAccuracy = $user->placeByAttribute('accuracy');?> место
                <?php if(Users::$placeImage[$placeAccuracy]) : ?>
                    <div class="medal"><img src="/images/<?php echo Users::$placeImage[$placeAccuracy]?>" /></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="name">Мудрость</div>
            <td class="desc">Число навыков, которыми Вы овладели</td>
            <td class="value"><?php echo $user->wisdom; ?></td>
            <td class="place">
                <?php echo $placeWisdom = $user->placeByAttribute('wisdom');?> место
                <?php if(Users::$placeImage[$placeWisdom]) : ?>
                    <div class="medal"><img src="/images/<?php echo Users::$placeImage[$placeWisdom]?>" /></div>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php if(Yii::app()->user->id == $user->id) : ?>
        <div class="link-progress">
            <div>Поделитесь с друзьями своими достижениями: </div>
            <div><?php echo $this->createUrl('users/progress', array('key'=>$user->progressKey)); ?></div>
        </div>
    <?php endif; ?>
</div>