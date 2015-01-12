<?php

class WidgetClock
{
    public $params;
    function __construct($params) {
        $this->params = $params;
    }
    
    function draw($answers = null)
    {
        $path = '/'.Yii::app()->params['graphicWidgetsPath'].'/clock';
        $bigHandDegree = ($this->params['h']*30)+(($this->params['m']%60)*0.5);
        $smallHandDegree = 6 * $this->params['m'];
        $text = "<div class='graphic-widget'>";
            $text .= "<div class='clock'>";
                $text .= "<img src='$path/clock.png' />";
                $text .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.2'>";
                    $text .="<g>";
                        $text .= "<g style='fill:rgb(20,20,20); stroke:rgb(20,20,20)' transform='rotate($bigHandDegree, 100, 100)'>";
                            $text .= "<polygon points='94,46 100,40 106,46 106,118 94,118' style='stroke:none'/>";
                        $text .= "</g>";
                        $text .= "<g style='fill:rgb(20,20,20); stroke:rgb(20,20,20)' transform='rotate($smallHandDegree, 100, 100)'>";
                            $text .= "<polygon points='95.5,11.5 100,7 104.5,11.5 104.5,122 95.5,122' style='stroke:none'/>";
                        $text .= "</g>";
                        $text .= "<g class='axis'>";
                            $text .= "<circle cx='100' cy='100' r='7' style='fill:rgb(150,0,0); stroke:rgb(50,50, 50)' />";
                        $text .= "</g>";
                    $text .= "</g>";
                $text .= "</svg>";
            $text .= "</div>";
        $text .= "</div>";
        return $text;
    }
    
    function getAnswersAttrs()
    {
        return array();
    }
    
}

