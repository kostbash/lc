<?php

class MyWidgets {
    static function ProgressBarWithLimiter($begin, $end)
    {
       $begin = (int) $begin;
       $end = (int) $end;
       if($begin >= $end)
       {
           $begin_index = 0;
           $end_index = 1;
       }
       else
       {
           $begin_index = 1;
           $end_index = 0;
       }
       $result = "<div style='display:inline-block; min-width: 200px'>";
       $result .= "<div class='progress' style='margin: 5px;'>";
       $result .= "<div style='width:100% height: 100%; position: relative;'>";
       $result .= "<div style='width:$begin%; background: #f0ad4e; position: absolute;z-index: $begin_index;'> </div>";
       $result .= "<div style='width:$end%; background: #5cb85c; position: absolute;z-index: $end_index;'> </div>";
       $result .= '</div>';
       $result .= '</div>';
       $result .= "<div style='font-size: 11px; text-align:center; margin-left:5px'><span style='background: #f0ad4e;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> - Надо $begin%;&nbsp;&nbsp;<span style='background: #5cb85c;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> - Достигнуто $end%</div>";
       $result .= '</div>';
       return $result;
    }
}

?>
