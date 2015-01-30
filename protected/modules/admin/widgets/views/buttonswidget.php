<?php
foreach ($widgets as $key => $row) {

    // строим кнопки для отображения модального окна, для добавления виджета
    $html .= CHtml::ajaxLink($row, Yii::app()->createUrl('admin/exercises/buildbuttonswindow/', array('widget' => $row)), array(
                'success' => " function(data, form) 
			{ 
                            if(data.status!='error')
                            {
                                $('#_addmodal').html(data);
                                $('#add-modal-" . $row . "').modal('show');

                                textarea =  $('#Exercises_questions_0_text');
                                position_text =  textarea[0].selectionStart;
                                $('#textarea-position').val(position_text);
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