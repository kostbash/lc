<?php

Yii::import('zii.widgets.grid.CGridView');

class ZGridView extends CGridView
{
    const PAGE_SIZE_VAR_PREFIX = "Pagination";

    public $defaultPageSize = 10;
    public $pager = array(
	'class' => 'CLinkPager',
	'htmlOptions' => array('class' => 'pagination'),
	'header' => '',
	'nextPageLabel' => '&gt;',
	'prevPageLabel' => '&lt;',
	'firstPageLabel' => '&lt;&lt;',
	'lastPageLabel' => '&gt;&gt;',
	'hiddenPageCssClass' => 'disabled'
    );
    
    public $pagerCssClass = "pull-right";
    public $htmlOptions = array('class' => 'clearfix zgrid');
    public $cssFile = false;
    public $summaryText = false;
    public $itemsCssClass = 'table table-hover';
    public $ajaxUpdate = true;

    public function registerClientScript() {
        parent::registerClientScript();
        $cs=Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui.js");
    }
    
    public function init() {
            parent::init();
    }
    
    protected function createDataColumn($text)
    {
            if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/',$text,$matches))
                    throw new CException(Yii::t('zii','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
            $column=new ZDataColumn($this);
            $column->name=$matches[1];
            if(isset($matches[3]) && $matches[3]!=='')
                    $column->type=$matches[3];
            if(isset($matches[5]))
                    $column->header=$matches[5];
            return $column;
    }
    
    protected function initColumns()
    {
            if($this->columns===array())
            {
                    if($this->dataProvider instanceof CActiveDataProvider)
                            $this->columns=$this->dataProvider->model->attributeNames();
                    elseif($this->dataProvider instanceof IDataProvider)
                    {
                            // use the keys of the first row of data as the default columns
                            $data=$this->dataProvider->getData();
                            if(isset($data[0]) && is_array($data[0]))
                                    $this->columns=array_keys($data[0]);
                    }
            }
            $id=$this->getId();
            foreach($this->columns as $i=>$column)
            {
                    if(is_string($column))
                            $column=$this->createDataColumn($column);
                    else
                    {
                            if(!isset($column['class']))
                                    $column['class']='ZDataColumn';
                            $column=Yii::createComponent($column, $this);
                    }
                    if(!$column->visible)
                    {
                            unset($this->columns[$i]);
                            continue;
                    }
                    if($column->id===null)
                            $column->id=$id.'_c'.$i;
                    $this->columns[$i]=$column;
            }

            foreach($this->columns as $column)
                    $column->init();
    }
    
}

?>
