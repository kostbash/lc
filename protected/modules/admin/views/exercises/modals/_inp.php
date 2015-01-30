<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Правильный ответ</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('r'); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Ширина в пикселях</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('w'); ?>
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
