<?php

class WidgetInput
{
    public $params;
    public $answers;
    function __construct($params) {
        $this->params = $params;
    }
    
    function draw($answers=null, $numberOfExercise=null)
    {
        $text = "<div class='graphic-widget'>";
            $text .= "<div class='input'>";
                if($this->params['r']!=='' && $answers)
                {
                    $attrs = array();
                    $atts['value'] = '';
                    $atts['style'] = '';
                    $width = (int) $this->params['w'] ? (int) $this->params['w'] : (mb_strlen($this->params['r'], 'UTF-8') * 9)+14;
                    $key = key($answers);
                    $id = $answers[$key]->id;
                    if($width)
                    {
                        $attrs['style'] .= "width:{$width}px;";
                    }

                    if($id)
                    {
                        unset($answers[$key]);
                    }

                    $text .= CHtml::textField("Exercises[$numberOfExercise][answers][$id]", '', $attrs);
                }
            $text .= "</div>";
        $text .= "</div>";
        if($answers)
        {
            $this->answers = $answers;
        }
        return $text;
    }
    
    function getAnswersAttrs()
    {
        $attrs = array();
        if($this->params['r']!=='')
        {
            $attrs[] = $this->params['r'];
        }
        return $attrs;
    }
}

