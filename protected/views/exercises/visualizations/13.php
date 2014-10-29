<div class="bags-type">
    <div class="items">
        <?php
            $rightAnswers = $model->rightAnswers;
            shuffle($rightAnswers);
        ?>
        <?php foreach($rightAnswers as $answer) : ?>
            <div class="item">
                <input class='hidden-answer' type="hidden" name="Exercises[<?php echo $key; ?>][answers][<?php echo $answer->id; ?>]" value="" />
                <div class="name"><?php echo $answer->name; ?></div>
                <?php if($answer->image) : ?>
                    <div class="image">
                        <?php
                            echo "<img src='/".Yii::app()->params['WordsImagesPath']."/$answer->image' />";
                        ?>
                    </div>
                <?php endif; ?>
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
            <?php
                if($bag->image)
                {
                    $imageUrl = Yii::app()->params['WordsImagesPath']."/".$bag->image;
                    $maxWidthBagImage = 238;
                    $minHeightBag = 121;
                    $imageSize = getimagesize($imageUrl);
                    if($imageSize[0] > $maxWidthBagImage)
                    {
                        $width = $maxWidthBagImage;
                        $devider = $imageSize[0]/$maxWidthBagImage;
                        $height = round($imageSize[1]/$devider, 0, PHP_ROUND_HALF_DOWN);
                    }
                    else
                    {
                        $width = $imageSize[0];
                        $height = $imageSize[1];
                    }
                    $styleBag = $height > $minHeightBag ? "style='min-height: {$height}px;'" : '';
                }
                else
                {
                    $styleBag = '';
                }
            ?>
            <div class="bag<?php echo $additionClass; ?>" data-index='<?php echo $bag->id; ?>'>
                <div class="head"><?php echo $bag->name; ?></div>
                <div class="bag-drop" <?php if($styleBag) echo $styleBag; ?>>
                    <?php if($bag->image) : ?>
                        <div class="image">
                            <?php
                                echo "<img width='$width' height='$height' src='/$imageUrl' />";
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="white-back"></div>
                </div>
            </div>
        <?php ++$i; endforeach; ?>
    </div>
</div>