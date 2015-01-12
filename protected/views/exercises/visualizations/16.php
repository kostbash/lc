<div class="universal clearfix">
    <div class="text">
        <?php $gw = new GraphicWidgets($model->Questions[0]->text, $model, $key);?>
        <?php echo $gw->draw();?>
    </div>
</div>