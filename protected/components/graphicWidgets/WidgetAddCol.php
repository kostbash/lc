<?php

class WidgetAddCol extends GraphicWidget
{

    protected $INPS_PARAM = 'a';

    public $params;
    public $answers;
    public $values = array();
    public static $key = 0;

    function __construct($params)
    {

        $this->params = $params;
        $this->setValues();
    }

    function draw($answers = null, $numberOfExercise = null)
    {
        $this->addInps($answers, $numberOfExercise);
        $text = "<div class='graphic-widget'>";
        $text .= "<div class='add_col'>";
        $text .= "<table>";
        $text .= "<tbody>"; 
        $text .= "<tr>";
        $text .= "<td>&nbsp;" . $this->values['mul1'] . "</td>";
        $text .= "</tr>";
        $text .= "<tr>";
        $text .="<td style=\"position:relative;\"><div class='minus'>+</div><div class='under'> " . $this->values['mul2'] . "</div></td>";
        $text .= "</tr>";
        $text .= "<tr>";
        $text .= "<td colspan='" . $this->values['mul1Length'] . "' class='residue'>" . $this->values['rightAnswer'] . "</td>";
        $text .= "</tr>";
        $text .= "</tbody>";
        $text .= "</table>";
        $text .= "</div>";
        $text .= "</div>";
        return $text;
    }

    function setValues()
    {
        $mul1 = (int) $this->params['s1'];
        $mul2 = (int) $this->params['s2'];
        $this->values['subs'] = array();
        if ($mul1 >= 0 && $mul2 >= 0)
        {
            $mul1 = (string) $mul1;
            $mul2 = (string) $mul2;
            $mul2Length = strlen($mul2);
            $rightAnswer = $mul1 + $mul2;
        }
        $this->values['mul1'] = $mul1;
        $this->values['mul2'] = $mul2;
        $this->values['mul2Length'] = $mul2Length;
        $this->values['rightAnswer'] = $rightAnswer;
    }

    // заменяет на инпуты
    function addInps($answers, $numberOfExercise)
    {
        $inps = $this->clearInps();
        if ($inps)
        {
            foreach ($inps as $k => $inp)
            {
                if ($k == 'r')
                {
                    $this->values['rightAnswer'] = $this->setPartialInput($this->values['rightAnswer'], $inp, $numberOfExercise, &$answers);
                }
                elseif (preg_match('#s(\d+)#', $k, $match))
                {
                    if ($this->values['mul' . $match[1]])
                    {
                        $this->values['mul' . $match[1]] = $this->setPartialInput($this->values['mul' . $match[1]], $inp, $numberOfExercise, &$answers);
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
            if ($key == 'r')
            {
                $ans = $this->setPartialAttrs($this->values['rightAnswer'], $inp);
                foreach ($ans as $a)
                    $attrs[] = $a;
            }
            elseif (preg_match('#s(\d+)#', $key, $match))
            {
                $subtractor = $this->values['mul' . $match[1]];
                //if($subtractor)
                {
                    $ans = $this->setPartialAttrs($subtractor, $inp);
                    foreach ($ans as $a)
                        $attrs[] = $a;
                }
            }
        } //print_r($attrs);
        return $attrs;
    }
}