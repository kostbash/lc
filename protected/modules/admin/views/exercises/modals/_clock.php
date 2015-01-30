<div class="row">
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::label('Часы', 'input'); ?>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('h'); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::label('Минуты', 'input'); ?>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('m'); ?>
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
