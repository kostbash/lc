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
        
        if($begin >= 0 && $begin <=9)
        {
            $need_width = 62;
            $need_margin_left = -$need_width + 22;
        
        }
        elseif($begin >=10 AND $begin <=99)
        {
            $need_width = 76;
            $need_margin_left = -$need_width + 28;
        }
        else
        {
            $need_width = 89;
            $need_margin_left = -$need_width + 35;
        }
        
        
        if($end >= 0 && $end <=9)
        {
            $achieved_width = 100;
            $achieved_margin_left = -$achieved_width + 21;
        
        }
        elseif($end >=10 AND $end <=99)
        {
            $achieved_width = 114;
            $achieved_margin_left = -$achieved_width + 29;
        }
        else
        {
            $achieved_width = 128;
            $achieved_margin_left = -$achieved_width + 35;
        }
        
        
        
        $result = "<div class='progress-bar-with-limiter'>";
            $result .= "<div class='full-percent'>";
                $result .= "<div class='percent'>100<span>%</span></div>";
            $result .= "</div>";
            $result .= "<div class='progress progress-striped active'>";
                $result .= "<div class='progress-bar progress-achieved' role='progressbar' aria-valuenow='$end' aria-valuemin='0' aria-valuemax='100' style='width: $end%; z-index: $end_index;'></div>";
                $result .= "<div class='progress-bar progress-need' role='progressbar' aria-valuenow='0' aria-valuemin='$begin' aria-valuemax='100' style='width: $begin%; z-index: $begin_index;'></div>";
            $result .= '</div>';
            $result .= "<div class='markers'>";
                $result .= "<div class='achieved' style='left: $end%; width: {$achieved_width}px; margin-left: {$achieved_margin_left}px'>Достигнуто <div class='percent'>$end<span>%</span></div></div>";
                $result .= "<div style='left: $end%;' class='arrow-down'></div>";
                $result .= "<div style='left: $begin%;' class='arrow-up'></div>";
                $result .= "<div class='need' style='left: $begin%; width: {$need_width}px; margin-left: {$need_margin_left}px'>Цель <div class='percent'>$begin<span>%</span></div></div>";
            $result .= "</div>"; 
        $result .= "</div>";
       return $result;
    }
    
//    static function ProgressBarWithLimiter($begin, $end)
//    {
//       $begin = (int) $begin;
//       $end = (int) $end;
//       if($begin >= $end)
//       {
//           $begin_index = 0;
//           $end_index = 1;
//       }
//       else
//       {
//           $begin_index = 1;
//           $end_index = 0;
//       }
//       $result = "<div style='display:inline-block; min-width: 200px'>";
//        $result .= "<div class='progress' style='margin: 5px;'>";
//            $result .= "<div style='width:100% height: 100%; position: relative;'>";
//                $result .= "<div style='width:$begin%; background: #f0ad4e; position: absolute;z-index: $begin_index;'> </div>";
//                $result .= "<div style='width:$end%; background: #5cb85c; position: absolute;z-index: $end_index;'> </div>";
//            $result .= '</div>';
//        $result .= '</div>';
//            $result .= "<div style='font-size: 11px; text-align:center; margin-left:5px'><span style='background: #f0ad4e;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> - Надо $begin%;&nbsp;&nbsp;<span style='background: #5cb85c;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> - Достигнуто $end%</div>";
//       $result .= '</div>';
//       return $result;
//    }
}

?>
