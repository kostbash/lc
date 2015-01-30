<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Делимое</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('did'); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Делитель</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('dir'); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Коды мест вставки полей ввода</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('inps[]'); ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('input').keyup(function(e) {
            if (e.keyCode == 13) {

                $nextInput = $(this).parent().parent().next().find('input');

                if ($nextInput.length > 0)
                    $nextInput.focus();
                else
                    $('#paste-button').focus();
            }
        });
    });
</script>
