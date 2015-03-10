<script type="text/javascript">
    $(function(){
        $('#confirmTeacherModal .confirmation .yes').live('click', function(){
            current = $(this);
            teacher = current.closest('.modal-content').find('.teacher');
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/children/confirmDealFromSite'); ?>',
                type:'POST',
                data: {id: teacher.data('id'), answer: 1, isTeacher: 1},
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                    {
                        $('#user-page-link a span').html('Преподаватель: '+teacher.html());
                        $('#confirmTeacherModal').modal('hide');
                    }
                }
            });
            return false;
        });
        $('#confirmTeacherModal .confirmation .no').live('click', function(){
            current = $(this);
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/children/confirmDealFromSite'); ?>',
                type:'POST',
                data: {id: current.closest('.modal-content').find('.teacher').data('id'), answer: 2},
                dataType: 'json',
                success: function(result) {
                    if(result.success)
                    {
                        $('#confirmTeacherModal').modal('hide');
                    }
                }
            });
            return false;
        });

        $('#confirmTeacherModal').modal('show');
    });
</script>

<div class="modal fade" id="confirmTeacherModal" tabindex="-1" role="dialog" aria-labelledby="confirmTeacherModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="regModelLabel">Вы получили запрос на подтверждение преподавателя с почтового ящика - <?php echo $newTeacher->Teacher->email; ?></h4>
            </div>
            <div class="modal-body clearfix">
                <div class="teacher" style="font-size: 28px; text-align: center" data-id="<?php echo $newTeacher->id; ?>">
                    <?php echo $newTeacher->Teacher->email; ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="confirmation">
                    <a href="#" class="btn btn-success yes">Да, это мой преподаватель</a>
                    <a href="#" class="btn btn-danger no">Нет, это НЕ мой преподаватель</a>
                </div>
            </div>
        </div>
    </div>
</div>