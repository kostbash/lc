<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Название элементов выбора через запятую</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('v'); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Номер выделенного по умолчанию элемента, счет с 1</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('s'); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 col-md-5">
        <label for="">Номер правильного ответа, счет с 1</label>
    </div>
    <div class="col-lg-5 col-md-5">
        <?php echo CHtml::textField('a'); ?>
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
