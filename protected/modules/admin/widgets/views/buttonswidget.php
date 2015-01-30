<?php
foreach ($widgets as $key => $row) {

    $html .= CHtml::ajaxLink($row, Yii::app()->createUrl('admin/exercises/buildbuttonswindow/', array('widget' => $row)), array(
                'success' => " function(data, form) 
			{ 
                            if(data.status!='error')
                            {
                                $('#_addmodal').html(data);
                                $('#add-modal-" . $row . "').modal('show');
                                
                               
                            }
			}"
                    ), array(
                'type' => 'submit',
                'class' => 'btn btn-warning',
                    )
            ) . " ";
}

echo $html;
?>

<div id="_addmodal"></div>