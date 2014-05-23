<?php

class MyWidgets {
    static function ProgressBarWithLimiter($begin, $end)
    {
       $begin = (int) $begin;
       $end = (int) $end;
       if($end >= $begin) {
           $outer = $end;
           $inner = $begin;
           $outerClass = 'success';
           $innerColor = '#f0ad4e';
       } else {
           $outer = $begin;
           $inner = $end;
           $outerClass = 'warning';
           $innerColor = '#5cb85c';
       }
       $result = "<div style='display:inline-block; min-width: 200px'>";
       $result .= "<div class='progress' style='position: relative; margin: 5px;'>";
       $result .= "<div class='progress-bar progress-bar-$outerClass' style='width: {$outer}%'>";
       $result .= "<div style='width:$inner%; background: $innerColor;'> </div>";
       $result .= '</div>';
       $result .= '</div>';
       $result .= "<div style='font-size: 11px; text-align:center; margin-left:5px'><span style='background: #f0ad4e;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> - Надо $begin%;&nbsp;&nbsp;<span style='background: #5cb85c;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> - Достигнуто $end%</div>";
       $result .= '</div>';
       return $result;
    }
}

?>
