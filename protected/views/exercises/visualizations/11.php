<div class="pick-area">
    <img src="<?php echo $model->Map->mapImageLink; ?>" />
    <?php @$imageSize = getimagesize($model->Map->getMapImageLink(true)); ?>
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
                echo "<g data-val='$area->id' class='' data-key='$key'>$shape</g>";
            }
        ?>
    </svg>
    <?php echo CHtml::hiddenField("Exercises[$key][answers]", '', array('id'=>false, 'class'=>'hidden-answer', 'data-key'=>$key)); ?>
</div>