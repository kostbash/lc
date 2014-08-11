<script type="text/javascript">
    $(function(){
       $('.teacher .confirmation .yes').live('click', function(){
           current = $(this);
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/studentsofteacher/confirmdeal'); ?>',
                type:'POST',
                data: {id: current.closest('.teacher').data('id'), answer: 1},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                    {
                        current.closest('.deal').remove();
                    }
                }
             });
           return false;
       }); 
       $('.teacher .confirmation .no').live('click', function(){
           current = $(this);
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/studentsofteacher/confirmdeal'); ?>',
                type:'POST',
                data: {id: current.closest('.teacher').data('id'), answer: 2},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                    {
                        current.closest('.deal').remove();
                    }
                }
             });
           return false;
       }); 
       
       $('.parent .confirmation .yes').live('click', function(){
           current = $(this);
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/childrenofparent/confirmdeal'); ?>',
                type:'POST',
                data: {id: current.closest('.parent').data('id'), answer: 1},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                    {
                        $('.parent').closest('.deal').remove();
                    }
                }
             });
           return false;
       }); 
       $('.parent .confirmation .no').live('click', function(){
           current = $(this);
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/childrenofparent/confirmdeal'); ?>',
                type:'POST',
                data: {id: current.closest('.parent').data('id'), answer: 2},
                dataType: 'json',
                success: function(result) { 
                    if(result.success)
                    {
                        current.closest('.deal').remove();
                    }
                }
             });
           return false;
       }); 
    });
</script>
<div class="widget" style="padding-top: 10px">
    <div class="page-header clearfix">
        <h2>Предложения"</h2>
    </div>
    <div class="section">
        <h3 class='head'>Учителя</h3>
        <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => new CArrayDataProvider($newTeachers),
                'itemView' => '_confirm_teacher',
                'template' => '{items}{pager}',
            ));
        ?>
    </div>
    
    <div class="section">
        <h3 class='head'>Родители</h3>
        <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => new CArrayDataProvider($newParent),
                'itemView' => '_confirm_parent',
                'template' => '{items}{pager}',
            ));
        ?>
    </div>
</div>