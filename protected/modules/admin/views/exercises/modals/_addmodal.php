<!-- EDITOR MESSAGE MODAL -->
<div class="modal fade" id="add-modal-<?php echo $widget;?>" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-file-o"></i> Заполните поля</h4>
			</div>

			<div class="modal-body">
				<form id="add-modal-form-<?php echo $widget;?>" enctype="multipart/form-data">
					<input type='hidden' name='widget' value='<?php echo $widget;?>'/>
					<?php 
						$objWidget->drawModalBody();
					?>
				</form>
			</div>
			
			<div class="modal-footer clearfix">
				<?php echo CHtml::ajaxButton('Вставить', CController::createUrl('exercises/buildexpressionbuttons/', array('widget'=>$widget)), array(
						'type' => 'POST',
						'data' => 'js:($("#add-modal-form-'.$widget.'").serialize())',
						'dataType' => 'text',
						'complete' => " function(data) 
							{
								console.log(data);
								//var obj = $.parseJSON(data.responseText);

								$('#Exercises_questions_0_text').val($('#Exercises_questions_0_text').val()+data.responseText);
								$('#add-modal-".$widget."').modal('hide');
								
							}"
						), array(
							'type' => 'submit',
							'id' => 'pastebutton',
							'class' => 'btn btn-primary pull-left',
						)
					);
				?>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Отменить</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->