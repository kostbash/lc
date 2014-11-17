<div class="hotmap-ordering">
    <?php
        $rightAnswers = $model->rightAnswers;
        $mapsColors = array();
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
                    $mapsColors[$answer->id_area] = $color = rand(60, 190).",".rand(60, 190).",".rand(60, 190);
                    echo "<g data-color='rgb($color)' style='stroke: rgb($color); fill: rgba($color, 0.4); ' data-id='$area->id' class='area' data-key='$key'>$shape</g>";
                }
            ?>
        </svg>
    </div>
    <div class="bag">
        <div class="head">Перенесите сюда</div>
        <div class="bag-drop">
            <div class="dropped-items"></div>
        </div>
    </div>
    <div class="items">
        <?php foreach($rightAnswers as $answer) : ?>
            <div class="item" data-area="<?php echo $answer->id_area; ?>" style="color: rgb(<?php echo $mapsColors[$answer->id_area]; ?>); border-color: rgb(<?php echo $mapsColors[$answer->id_area]; ?>);">
                <input class='hidden-answer' type="hidden" name="Exercises[<?php echo $key; ?>][answers][<?php echo $answer->id; ?>]" value="" />
                <?php echo $answer->name; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>