<?php

Yii::import('zii.widgets.grid.CDataColumn');

class ZDataColumn extends CDataColumn
{
    public $visibleCell = 'true';
    
    protected function renderDataCellContent($row,$data)
    {
        $visible=$this->evaluateExpression($this->visibleCell, array('data'=>$data,'row'=>$row));
        if($visible)
        {
            if($this->value!==null)
                $value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
            elseif($this->name!==null)
                $value=CHtml::value($data,$this->name);
            echo $this->grid->getFormatter()->format($value,$this->type, $data, $this->name);
        }
    }
}
