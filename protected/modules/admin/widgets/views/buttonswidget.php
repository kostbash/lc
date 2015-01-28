<?php

foreach($widgets as $key=>$row) {
	//$widget = $gw->pick($row, '');

	/*if($row=='select') 
	{
		$modal1=$widget->drawAddModal($row);
	}*/
	
	$html .= CHtml::ajaxLink($row, Yii::app()->createUrl('admin/exercises/buildbuttonswindow/', array('widget'=>$row)), array(
		'success' => " function(data, form) 
			{ 
				if(data.status!='error')
				{
					$('#_addmodal').html(data);
					$('#add-modal-".$row."').modal('show');
				}
			}"
		), array(
			'type' => 'submit',
			'class' => 'btn btn-warning',
		)
	)." ";

	
	//$html .= "<a href='#modal' role='button' class='btn btn-warning' data-toggle='modal' data-target='#add-modal-".$row."'>".$row."</a> ".$modal1;
}

echo $html;
?>
<div id="_addmodal"></div>