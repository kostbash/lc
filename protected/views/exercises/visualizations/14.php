<div class="hotmap-bags">
    <?php
        $rightAnswers = $model->rightAnswers;
    ?>
    <div class="left">Осталось: <span class="count"><?php echo count($rightAnswers); ?></span></div>
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
        <?php $imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
        <svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" width="<?php echo $imageSize[0]; ?>" height="<?php echo $imageSize[1]; ?>">
            <?php
                foreach($rightAnswers as $answer)
                {
                    $area = $answer->Area;
                    $coords = $area->cleanCoords;
                    if($area->shape==1)
                    {
                        $shape = "<circle cx='{$coords['cx']}' cy='{$coords['cy']}' r='{$coords['r']}'></circle>";
                    }
                    elseif($area->shape==2)
                    {
                        $shape = "<rect x='{$coords['x']}' y='{$coords['y']}' width='{$coords['width']}' height='{$coords['height']}'></rect>";
                    }
                    elseif($area->shape==3)
                    {
                       $shape = "<polygon points='{$coords['points']}'></polygon>";
                    }
                    echo "<g data-id='$area->id' class='area' data-key='$key'>$shape</g>";
                }
            ?>
        </svg>
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