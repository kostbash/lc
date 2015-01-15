<?php
require_once 'graphicWidgets/WidgetClock.php';
require_once 'graphicWidgets/WidgetDivCol.php';
require_once 'graphicWidgets/WidgetInput.php';
require_once 'graphicWidgets/WidgetExpression.php';

class GraphicWidgets
{
    public $exercise;
    public $rawText;
    public $answers;
    public $widgets = array();
    public $widgetsTexts = array();
    public $numberOfExercise = null;
    
    function __construct($rawText, Exercises $exercise=null, $numberOfExercise=null)
    {
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
        
        if($name==='clock')
        {
            $widget = new WidgetClock($params);
        }
        elseif($name==='div_col')
        {
            $widget = new WidgetDivCol($params);
        }
        elseif($name==='inp')
        {
            $widget = new WidgetInput($params);
        }
        elseif($name==='ex')
        {
            $widget = new WidgetExpression($params);
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
}

