<?php

class WidgetDivCol extends GraphicWidget
{

    protected $INPS_PARAM = 'inps';

    public $params;
    public $answers;
    public $values = array();

    function __construct($params)
    {
        $this->params = $params;
        $this->setValues();
    }

    function draw($answers = null, $numberOfExercise = null)
    {
        $this->addInps($answers, $numberOfExercise);
        $text = "<div class='graphic-widget monowidth'>";
        $text .= "<div class='div_col'>";
        if ($this->values['subs'])
        {
            $text .= "<table>";
            $text .= "<tbody>";
            foreach ($this->values['subs'] as $k => $sub)
            {
                if ($k == 0)
                {
                    $text .= "<tr>";
                    $text .= "<td>&nbsp;" . $this->values['dividend'] . "</td>";
                    $text .= "<td class='dir'>" . $this->values['divider'] . "</td>";
                    $text .= "<td> </td>";
                    $text .= "</tr>";
                    $text .= "<tr>";
                    $text .="<td><div class='minus'>-</div><div class='under'> " . $sub['subtractor'] . "</div></td>";
                    $text .="<td class='right'>" . $this->values['rightAnswer'] . "</td>";
                    $text .="<td> </td>";
                    $text .= "</tr>";
                }
                else
                {
                    $text .= "<tr>";
                    $text .= "<td colspan='" . $this->values['dividentLength'] . "'>" . str_repeat('&nbsp;', $k  + 1) . $sub['subtrahend'] . "</td>";
                    $text .= "</tr>";
                    $text .= "<tr>";
                    $text .= "<td colspan='" . $this->values['dividentLength'] . "'>" . str_repeat('&nbsp;', $k ) . "<div class='minus'>-</div><div class='under'> " . $sub['subtractor'] . "</div></td>";
                    $text .= "</tr>";
                }
                $k++;
            }
            $text .= "<tr>";
            $text .= "<td colspan='" . $this->values['dividentLength'] . "' class='residue'>" . str_repeat('&nbsp;', ($k - 1) + strlen($this->values['lastSubstractor'])) . $this->values['residue'] . "</td>";
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
        if ($dividend > 0 && $divider > 0 && $dividend >= $divider)
        {
            $dividend = (string) $dividend;
            $divider = (string) $divider;
            $dividentLength = strlen($dividend);
            $residue = 0; // остаток от деления
            $rightAnswer = floor($dividend / $divider);
            $k = 0;
            for ($i = 0; $i < $dividentLength; $i++)
            {
                $subtrahend = (int) ($residue . $dividend[$i]); // вычитаемое
                $residue = fmod($subtrahend, $divider); // остаток от деления
                $subtractor = floor($subtrahend / $divider) * $divider; // вычитатель
                if ($subtractor)
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
        $this->values['lastSubstractor'] = $this->values['subs'][$k - 1]['subtractor'];
        $this->values['rightAnswer'] = $rightAnswer;
    }

    // заменяет на инпуты
    function addInps($answers, $numberOfExercise)
    {
        $inps = $this->clearInps();
        if ($inps && $answers)
        {
            foreach ($inps as $k => $inp)
            {
                if ($k == 'a')
                {
                    $this->values['rightAnswer'] = $this->setPartialInput($this->values['rightAnswer'], $inp, $numberOfExercise, &$answers);
                    ;
                    unset($answers[$key]);
                }
                elseif (preg_match('#s(\d+)#', $k, $match))
                {
                    if ($this->values['subs'][$match[1]]['subtrahend'])
                    {
                        $this->values['subs'][$match[1]]['subtrahend'] = $this->setPartialInput($this->values['subs'][$match[1]]['subtrahend'], $inp, $numberOfExercise, &$answers);
                        unset($answers[$key]);
                    }
                }
                elseif (preg_match('#l(\d+)#', $k, $match))
                {
                    if ($this->values['subs'][$match[1] - 1]['subtractor'])
                    {
                        $this->values['subs'][$match[1] - 1]['subtractor'] = $this->setPartialInput($this->values['subs'][$match[1] - 1]['subtractor'], $inp, $numberOfExercise, &$answers);
                        ;
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
        foreach ($inps as $key => $inp)
        {
            if ($key == 'a')
            {
                $ans = $this->setPartialAttrs($this->values['rightAnswer'], $inp);
                foreach ($ans as $a)
                    $attrs[] = $a;
            }
            elseif (preg_match('#l(\d+)#', $key, $match))
            {
                $subtractor = $this->values['subs'][$match[1] - 1]['subtractor'];
                if ($subtractor)
                {
                    $ans = $this->setPartialAttrs($subtractor, $inp);
                    foreach ($ans as $a)
                        $attrs[] = $a;
                }
            }
            elseif (preg_match('#s(\d+)#', $key, $match))
            {
                $subtrahend = $this->values['subs'][$match[1]]['subtrahend'];
                if ($subtrahend)
                {
                    $ans = $this->setPartialAttrs($subtrahend, $inp);
                    foreach ($ans as $a)
                        $attrs[] = $a;
                }
            }
        }
        return $attrs;
    }

}
