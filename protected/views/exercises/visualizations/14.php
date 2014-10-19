<div class="hotmap-bags">
    <?php
        $rightAnswers = $model->rightAnswers;
    ?>
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
                        $next += 4;
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