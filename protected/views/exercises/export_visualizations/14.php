<div class="hotmap-bags">
    <?php
        $rightAnswers = $model->rightAnswers;
    ?>
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
        <?php $imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
    </div>
    <div class="items">
        <?php foreach($rightAnswers as $answer) : ?>
            <div class="item" data-area="<?php echo $answer->id_area; ?>">
                <input class='hidden-answer' type="hidden" name="Exercises[<?php echo $key; ?>][answers][<?php echo $answer->id; ?>]" value="" />
                <?php echo $answer->name; ?>
            </div>
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