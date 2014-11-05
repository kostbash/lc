<div class="bags-type">
    <div class="items">
        <b>Предметы: </b>
        <?php
            $rightAnswers = $model->rightAnswers;
            shuffle($rightAnswers);
        ?>
        <?php foreach($rightAnswers as $answer) : ?>
            <div class="item"><?php echo $answer->name; ?></div>
        <?php endforeach; ?>
    </div>
    
    <div class="answer-head">Ответ :</div>
    
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