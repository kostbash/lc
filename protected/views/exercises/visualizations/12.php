<div class="hotmap-items">
    <div class="areas">
        <img src="<?php echo $model->Map->mapImageLink; ?>" />
        <?php $imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
        <svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" width="<?php echo $imageSize[0]; ?>" height="<?php echo $imageSize[1]; ?>">
            <?php
                foreach($model->Map->Areas as $area)
                {
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
                    $color = rand(60, 190).",".rand(60, 190).",".rand(60, 190);
                    echo "<g data-color='rgb($color)' style='stroke: rgb($color); fill: rgba($color, 0.4); ' data-id='$area->id' class='area' data-key='$key'>$shape</g>";
                }
            ?>
        </svg>
    </div>
    <div class="items">
        <?php
            $rightAnswers = $model->rightAnswers;
            shuffle($rightAnswers);
        ?>
        <?php foreach($rightAnswers as $answer) : ?>
            <div class="item without-area">
                <input class='hidden-answer' type="hidden" name="Exercises[<?php echo $key; ?>][answers][<?php echo $answer->id; ?>]" value="" />
                <?php if($answer->image) : ?>
                    <div class="image">
                        <?php
                            echo "<img src='/".Yii::app()->params['WordsImagesPath']."/$answer->image' />";
                        ?>
                    </div>
                <?php endif; ?>
                <div class="name"><?php echo $answer->name; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>