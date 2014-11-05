<div class="hotmap-bags">
    <?php
        $rightAnswers = $model->rightAnswers;
    ?>
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
    </div>
    
    <div class="items">
        <b>Для распределения: </b>
        <?php foreach($rightAnswers as $answer) : ?>
            <div class="item"><?php echo $answer->name; ?></div>
        <?php endforeach; ?>
    </div>
    
    <div class="bags clearfix">
        <?php 
            $i = 1;
            $next = 1;
        ?>
        <?php foreach($model->Bags as $bag) : ?>
            <?php
                if($i==$next)
                    {
                        $additionClass = ' first-in-line';
                        $next += 3;
                    }
                    else
                    {
                        $additionClass = '';
                    }
            ?>
            <div class="bag<?php echo $additionClass; ?>" data-index='<?php echo $bag->id; ?>'>
                <div class="head"><?php echo $bag->name; ?></div>
                <div class="bag-drop"></div>
            </div>
        <?php ++$i; endforeach; ?>
    </div>
</div>