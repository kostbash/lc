<script type="text/javascript">
    $(function(){
       $('#confirmParentModal .confirmation .yes').live('click', function(){
           current = $(this);
           parent = current.closest('.modal-content').find('.parent');
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/children/confirmDealFromSite'); ?>',
                type:'POST',
                data: {id: parent.data('id'), answer: 1},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                    {
                        $('#user-page-link a span').html('Родитель: '+parent.html());
                        $('#confirmParentModal').modal('hide');
                    }
                }
             });
           return false;
       }); 
       $('#confirmParentModal .confirmation .no').live('click', function(){
           current = $(this);
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/children/confirmDealFromSite'); ?>',
                type:'POST',
                data: {id: current.closest('.modal-content').find('.parent').data('id'), answer: 2},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                    {
                        $('#confirmParentModal').modal('hide');
                    }
                }
             });
           return false;
       });
       
       $('#confirmParentModal').modal('show');
    });
</script>

<div class="modal fade" id="confirmParentModal" tabindex="-1" role="dialog" aria-labelledby="confirmParentModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="regModelLabel">Вы получили запрос на подтверждение родителя с почтового ящика - <?php echo $newParent->Parent->email; ?></h4>
      </div>
      <div class="modal-body clearfix">
          <div class="parent" data-id="<?php echo $newParent->id; ?>">
            <?php echo $newParent->Parent->email; ?>
          </div>
      </div>
      <div class="modal-footer">
        <div class="confirmation">
            <a href="#" class="btn btn-success yes">Да, это мой родитель</a>
            <a href="#" class="btn btn-danger no">Нет, это НЕ мой родитель</a>
        </div>
      </div>
    </div>
  </div>
</div>