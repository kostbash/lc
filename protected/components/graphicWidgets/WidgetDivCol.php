<?php

class WidgetDivCol
{
    public $params;
    public $answers;
    public $values = array();
    function __construct($params) {
        $this->params = $params;
        $this->setValues();
    }
    
    function draw($answers=null, $numberOfExercise=null)
    {
        $this->addInps($answers, $numberOfExercise);
        $text = "<div class='graphic-widget'>";
            $text .= "<div class='div_col'>";
                if($this->values['subs'])
                {
                    $text .= "<table>";
                    $text .= "<tbody>";
                        foreach($this->values['subs'] as $k => $sub)
                        {
                            if($k==0)
                            {
                                $text .= "<tr>";
                                    $text .= "<td>&nbsp;".$this->values['dividend']."</td>";
                                    $text .= "<td class='dir'>".$this->values['divider']."</td>";
                                    $text .= "<td> </td>";
                                $text .= "</tr>";
                                $text .= "<tr>";
                                    $text .="<td><div class='minus'>-</div><div class='under'> ".$sub['subtractor']."</div></td>";
                                    $text .="<td class='right'>".$this->values['rightAnswer']."</td>";
                                    $text .="<td> </td>";
                                $text .= "</tr>";
                            }
                            else
                            {
                                $text .= "<tr>";
                                    $text .= "<td colspan='".$this->values['dividentLength']."'>".str_repeat('&nbsp;', $k*2+1).$sub['subtrahend']."</td>";
                                $text .= "</tr>";
                                $text .= "<tr>";
                                    $text .= "<td colspan='".$this->values['dividentLength']."'>".str_repeat('&nbsp;', $k*2)."<div class='minus'>-</div><div class='under'> ".$sub['subtractor']."</div></td>";
                                $text .= "</tr>";
                            }
                            $k++;
                        }
                        $text .= "<tr>";
                            $text .= "<td colspan='".$this->values['dividentLength']."' class='residue'>".str_repeat('&nbsp;', ($k-1)*2+strlen($this->values['lastSubstractor'])*2-1).$this->values['residue']."</td>";
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
        $dividend = (int) $this->params['did'];
        $divider = (int) $this->params['dir'];
        $this->values['subs'] = array();
        if($dividend>0 && $divider>0 && $dividend >= $divider)
        {
            $dividend = (string) $dividend;
            $divider = (string) $divider;
            $dividentLength = strlen($dividend);
            $residue = 0; // остаток от деления
            $rightAnswer = floor($dividend/$divider);
            $k = 0;
            for($i=0; $i<$dividentLength; $i++)
            {
                $subtrahend = (int) ($residue.$dividend[$i]); // вычитаемое
                $residue = fmod($subtrahend, $divider); // остаток от деления
                $subtractor = floor($subtrahend/$divider) * $divider; // вычитатель
                if($subtractor)
                {
                    $this->values['subs'][$k]['subtrahend'] = $subtrahend;
                    $this->values['subs'][$k]['subtractor'] = $subtractor;
                    $k++;
                }
            }
            $this->values['residue'] = $residue;
        }
        $this->values['dividend'] = $dividend;
        $this->values['divider'] = $divider;
        $this->values['dividentLength'] = $dividentLength;
        $this->values['lastSubstractor'] = $this->values['subs'][$k-1]['subtractor'];
        $this->values['rightAnswer'] = $rightAnswer;
    }
    
    // заменяет на инпуты
    function addInps($answers, $numberOfExercise)
    {
        $inps = $this->clearInps();
        if($inps && $answers)
        {
            foreach($inps as $inp)
            {
                $key = key($answers);
                $answer = CHtml::textField("Exercises[$numberOfExercise][answers][".$answers[$key]->id."]", '', array('style'=>"width:" . (mb_strlen($answers[$key]->answer, 'UTF-8') * 9+4) . "px;"));
                if($inp=='a')
                {
                    $this->values['rightAnswer'] = $answer;
                    unset($answers[$key]);
                }
                elseif(preg_match('#s(\d+)#', $inp, $match))
                {
                    if($this->values['subs'][$match[1]]['subtrahend'])
                    {
                        $this->values['subs'][$match[1]]['subtrahend'] = $answer;
                        unset($answers[$key]);
                    }
                }
                elseif(preg_match('#l(\d+)#', $inp, $match))
                {
                    if($this->values['subs'][$match[1]-1]['subtractor'])
                    {
                        $this->values['subs'][$match[1]-1]['subtractor'] = $answer;
                        unset($answers[$key]);
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
        foreach($inps as $inp)
        {
            if($inp=='a')
            {
                $attrs[] = $this->values['rightAnswer'];
            }
            elseif(preg_match('#l(\d+)#', $inp, $match))
            {
                $subtractor = $this->values['subs'][$match[1]-1]['subtractor'];
                if($subtractor)
                {
                    $attrs[] = $subtractor;
                }
            }
            elseif(preg_match('#s(\d+)#', $inp, $match))
            {
                $subtrahend = $this->values['subs'][$match[1]]['subtrahend'];
                if($subtrahend)
                {
                    $attrs[] = $subtrahend;
                }
            }
        }
        return $attrs;
    }
    
    function clearInps()
    {
        $inps = array();
        if($this->params['inps'])
        { 
            return $this->params['inps'];
//            $this->params['inps'];
//            $dirtyInps = explode(',', $this->params['inps']);
//            if($dirtyInps)
//            {
//                foreach($dirtyInps as $dirtyInp)
//                {
//                    $inps[] = trim($dirtyInp);
//                }
//            }
        }
        return array_unique($inps);
    }
    
        function drawModalBody() {

        echo '<div class="row">
                    <div class="col-lg-5 col-md-5">
                            <label for="">Делимое</label>
                    </div>
                    <div class="col-lg-5 col-md-5">
                            <input type="text" name="did"/>
                    </div>
            </div>
            <div class="row">
                    <div class="col-lg-5 col-md-5">
                            <label for="">Делитель</label>
                    </div>
                    <div class="col-lg-5 col-md-5">
                            <input type="text" name="dir"/>
                    </div>
            </div>
            <div class="row">
                    <div class="col-lg-5 col-md-5">
                            <label for="">Коды мест вставки полей ввода</label>
                    </div>
                    <div class="col-lg-5 col-md-5">
                            <input type="text" name="inps[]"/>
                    </div>
            </div>
            ';
    }
}

