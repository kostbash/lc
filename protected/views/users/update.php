<div class="widget" style="padding-top: 10px">
<div class="page-header clearfix">
    <h2>Профиль пользователя "<?php echo $user->email; ?>"</h2>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>