<?php
require_once 'graphicWidgets/WidgetClock.php';
require_once 'graphicWidgets/WidgetDivCol.php';
require_once 'graphicWidgets/WidgetMultCol.php';
require_once 'graphicWidgets/WidgetAddCol.php';
require_once 'graphicWidgets/WidgetSubCol.php';
require_once 'graphicWidgets/WidgetInput.php';
require_once 'graphicWidgets/WidgetExpression.php';
require_once 'graphicWidgets/WidgetSelect.php';
require_once 'graphicWidgets/WidgetRadio.php';

class GraphicWidgets
{
    public $exercise;
    public $rawText;
    public $answers;
    public $widgets = array();
    public $widgetsTexts = array();
    public $numberOfExercise = null;
    public static $key = 0;
    
    function __construct($rawText, Exercises $exercise=null, $numberOfExercise=null)
    {
        self::$key = 0;
        if($exercise)
        {
            $this->exercise = $exercise;
            $this->answers = $exercise->rightAnswers;
            $this->numberOfExercise = (int) $numberOfExercise;
        }
        $this->rawText = $rawText;
        $this->setWidgets();
    }
    
    function setWidgets()
    {
        preg_match_all("#\[([\d\w]*):\{(.*)\}\]#uUm", $this->rawText, $matches, PREG_SET_ORDER);
        foreach($matches as $match)
        {
            $widget = $this->pick($match[1], CJSON::decode("{".$match[2]."}"));
            if($widget)
            {
                $this->widgetsTexts[] = $match[0];
                $this->widgets[] = $widget;
            }
        }
    }
    
    function pick($name, $params)
    {
        $widget = null;
        
        switch($name)
        {
            case "clock": $widget = new WidgetClock($params); break;
            case "div_col": $widget = new WidgetDivCol($params); break;
            case "inp": $widget = new WidgetInput($params); break;
            case "ex": $widget = new WidgetExpression($params); break;
            case "mult_col": $widget = new WidgetMultCol($params); break;
            case "add_col": $widget = new WidgetAddCol($params); break;
            case "sub_col": $widget = new WidgetSubCol($params); break;
            case "select": $widget = new WidgetSelect($params); break;
            case "radio": $widget = new WidgetRadio($params); break;
        }
        
        return $widget;
    }
    
    function draw()
    {
        foreach($this->widgets as $n => $widget)
        {
            $len = mb_strlen($this->widgetsTexts[$n], 'UTF-8');
            $begin = mb_strstr($this->rawText, $this->widgetsTexts[$n], true, 'UTF-8');
            $end = mb_substr(mb_strstr($this->rawText, $this->widgetsTexts[$n], false, 'UTF-8'), $len);
            $this->rawText = $begin . $widget->draw($this->answers, $this->numberOfExercise) . $end;
            $this->answers = $widget->answers;
        }
        return $this->rawText;
    }
    
    function getAnswersAttrs()
    {
        $attrs = array();
        foreach($this->widgets as $widget)
        {
            $attrs = array_merge($attrs, $widget->getAnswersAttrs());
        }
        return $attrs;
    }
	
	public function getAllWidgetsName()
    {
		$arr=array("clock","div_col","inp","ex","mult_col","add_col","sub_col", "select", "radio");
		
		return $arr;
    }
}

