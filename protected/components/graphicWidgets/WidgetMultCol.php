<?php

class WidgetMultCol
{
    public $params;
    public $answers;
    public $values = array();
    public static $key = 0;
    function __construct($params) {
        $this->params = $params;
        $this->setValues();
    }
    
    function draw($answers=null, $numberOfExercise=null)
    { 
        $this->addInps($answers, $numberOfExercise);
        $text = "<div class='graphic-widget'>";
            $text .= "<div class='mult_col'>";
                if($this->values['subs'])
                {
                    $text .= "<table>";
                    $text .= "<tbody>"; //print_r($this->values['subs']);
                    $text .= "<tr>";
                        $text .= "<td>&nbsp;".$this->values['mul1']."</td>";
                    $text .= "</tr>";
                    $text .= "<tr>";
                        $text .="<td style=\"position:relative;\"><div class='minus'>x</div><div class='under'> ".$this->values['mul2']."</div></td>";
                    $text .= "</tr>";
                        foreach($this->values['subs'] as $k => $sub)
                        {
                            
                            {
                                $text .= "<tr>";
                                $plus = "";//"<div class='plus'>+</div>";
                                if($k==0) $plus = "";
                                    $text .= "<td style='padding-right:".($k*12)."px;position:relative;' colspan='".$this->values['mul1Length']."'>$plus".($k==count($this->values['subs'])-1?"<div class='under'>":"").$sub['subtrahend'].($k==count($this->values['subs'])-1?"</div>":"")."</td>";
                                $text .= "</tr>";
                            }
                            $k++;
                        }
                        $text .= "<tr>";
                            $text .= "<td colspan='".$this->values['mul1Length']."' class='residue'>".$this->values['rightAnswer']."</td>";
                        $text .= "</tr>";
                    $text .= "</tbody>";
                    $text .= "</table>";
                }
            $text .= "</div>";
        $text .= "</div>";
        return $text;
    }
    
    function setValues()
    {
        $mul1 = (int) $this->params['m1'];
        $mul2 = (int) $this->params['m2'];
        $this->values['subs'] = array();
        if($mul1>0 && $mul2>0)
        {
            $mul1 = (string) $mul1;
            $mul2 = (string) $mul2;
            $mul2Length = strlen($mul2);
            $rightAnswer = $mul1*$mul2;
            $k = 0;
            for($i=$mul2Length-1; $i>=0; $i--)
            {
                $subtrahend = (int) ($mul1*$mul2[$i]); // вычитаемое
                
                $this->values['subs'][$k]['subtrahend'] = $subtrahend;
                $k++;
                
            }
        }
        $this->values['mul1'] = $mul1;
        $this->values['mul2'] = $mul2;
        $this->values['mul2Length'] = $mul2Length;
        $this->values['rightAnswer'] = $rightAnswer;
    }
    function setPartialInput($value, $inputs,$numberOfExercise, &$answers)
    {//print "COUNT=";print_r(count($answers));
        //print $value.print_r($inputs,true)."<br>";
        $result = "";
        $value = (string) $value;
        if(count($inputs))
        {
        for($i = 0;$i<strlen($value);$i++)
        {
            $ans = $value[$i]; //print $answer."=";
            if(in_array(strlen($value)-$i, $inputs))
            {
                //print '['.self::$key."]=".(isset($answers[self::$key])?"1":"0")."<br>";
                $ans = CHtml::textField("Exercises[$numberOfExercise][answers][".$answers[GraphicWidgets::$key]->id."]", '', array('style'=>"width:" . (12) . "px;", "key"=>GraphicWidgets::$key));
                unset($answers[GraphicWidgets::$key]);
                GraphicWidgets::$key++; 
            }
            else
                $ans = "<span class='num'>".$ans."</span>";;
            $result = $result.$ans;
        }
        }
        else
        {
            //print '['.self::$key."]=".(isset($answers[self::$key])?"1":"0")."<br>";
            $result = CHtml::textField("Exercises[$numberOfExercise][answers][".$answers[GraphicWidgets::$key]->id."]", '', array('style'=>"letter-spacing:3px;width:" . (mb_strlen($answers[GraphicWidgets::$key]->answer, 'UTF-8') * 12) . "px;", "key"=>GraphicWidgets::$key));
            unset($answers[GraphicWidgets::$key]);
            GraphicWidgets::$key++;
        }
        return $result;
    }
    function setPartialAttrs($value, $inputs)
    {//print_r($answer);
        //print $value.print_r($inputs,true)."<br>";
        $result = array();
        $value = (string) $value;
        if(count($inputs))
        {
        for($i = 0;$i<strlen($value);$i++)
        {
            $ans = $value[$i]; //print $answer."=";
            if(in_array(strlen($value)-$i, $inputs))
            {
                $result []= $ans;
                //$ans = CHtml::textField("Exercises[$numberOfExercise][answers][".$answer->id."]", '', array('style'=>"width:" . (1 * 9+4) . "px;"));
            }
            
        }
        }
        else
        {
            $result[] = $value;
        }
        return $result;
    }
    // заменяет на инпуты
    function addInps($answers, $numberOfExercise)
    {
        $inps = $this->clearInps();
        if($inps )
        { //print "ANSWERS";print_r($answers);print "|";
            
            foreach($inps as $k=>$inp)
            {
                //$key = key($answers);
                //$answer = CHtml::textField("Exercises[$numberOfExercise][answers][".$answers[$key]->id."]", '', array('style'=>"width:" . (mb_strlen($answers[$key]->answer, 'UTF-8') * 9+4) . "px;"));
                //print "$inp<br/>";
                if($k=='r')
                {
                    $this->values['rightAnswer'] = $this->setPartialInput($this->values['rightAnswer'], $inp,$numberOfExercise,&$answers);
                    
                }
                elseif(preg_match('#m(\d+)#', $k, $match))
                {
                    if($this->values['mul'.$match[1]])
                    {
                        $this->values['mul'.$match[1]] = $this->setPartialInput($this->values['mul'.$match[1]], $inp,$numberOfExercise,&$answers);
                        
                    }
                }
                elseif(preg_match('#l(\d+)#', $k, $match))
                {
                    if($this->values['subs'][$match[1]-1]['subtrahend'])
                    {
                        $this->values['subs'][$match[1]-1]['subtrahend'] = $this->setPartialInput($this->values['subs'][$match[1]-1]['subtrahend'], $inp,$numberOfExercise,&$answers);;
                        
                    }
                }
            }
        }
        $this->answers = $answers;
    }
    
    function getAnswersAttrs()
    {
        $attrs = array();
        $inps = $this->clearInps();
        foreach($inps as $key=>$inp)
        {
            if($key=='r')
            {
                $ans = $this->setPartialAttrs($this->values['rightAnswer'], $inp);
                foreach($ans as $a)
                    $attrs[] = $a;
            }
            elseif(preg_match('#m(\d+)#', $key, $match))
            {
                $subtractor = $this->values['mul'.$match[1]];
                //if($subtractor)
                {
                    $ans = $this->setPartialAttrs($subtractor, $inp);
                    foreach($ans as $a)
                        $attrs[] = $a;
                }
            }
            elseif(preg_match('#l(\d+)#', $key, $match))
            { //echo $key;
                $subtrahend = $this->values['subs'][$match[1]-1]['subtrahend'];
                //if($subtrahend)
                {
                    $ans = $this->setPartialAttrs($subtrahend, $inp);
                    foreach($ans as $a)
                        $attrs[] = $a;
                }
            }
        } //print_r($attrs);
        return $attrs;
    }
    
    function clearInps()
    {
        $inps = array(); 
        if($this->params['a'])
        {
            return $this->params['a'];
            //print_r($this->params['a']);
            $dirtyInps = $this->params['a'];
            if($dirtyInps)
            {
                foreach($dirtyInps as $k=>$dirtyInp)
                {
                    
                        foreach($dirtyInp as $dirtyInp1)
                        {
                            $inps[] = $k."[".trim($dirtyInp1)."]";
                        }
                    
                }
            }
        } 
        return array_unique($inps);
    }
}

