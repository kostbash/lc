<?php

class GraphicWidget
{

    protected $INPS_PARAM = 'a';

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
        
    }

    function setValues()
    {
        
    }

    // заменяет на инпуты
    function addInps($answers, $numberOfExercise)
    {
        
    }

    function getAnswersAttrs()
    {
        
    }

    function clearInps()
    {
        $inps = array();
        if ($this->params[$this->INPS_PARAM])
        {
            $inps = $this->params[$this->INPS_PARAM];
        }
        return $inps;
    }

    function setPartialInput($value, $inputs, $numberOfExercise, &$answers)
    {
        $result = "";
        $value = (string) $value;
        if (count($inputs))
        {
            for ($i = 0; $i < strlen($value); $i++)
            {
                $ans = $value[$i]; //print $answer."=";
                if (in_array(strlen($value) - $i, $inputs))
                {
                    $ans = CHtml::textField("Exercises[$numberOfExercise][answers][" . $answers[GraphicWidgets::$key]->id . "]", '', array('style' => "width:" . (12) . "px;", "key" => GraphicWidgets::$key));
                    unset($answers[GraphicWidgets::$key]);
                    GraphicWidgets::$key++;
                }
                else
                    $ans = "<span class='num'>" . $ans . "</span>";;
                $result = $result . $ans;
            }
        }
        else
        {
            $result = CHtml::textField("Exercises[$numberOfExercise][answers][" . $answers[GraphicWidgets::$key]->id . "]", '', array('style' => "letter-spacing:3px;width:" . (mb_strlen($answers[GraphicWidgets::$key]->answer, 'UTF-8') * 12) . "px;", "key" => GraphicWidgets::$key));
            unset($answers[GraphicWidgets::$key]);
            GraphicWidgets::$key++;
        }
        return $result;
    }

    function setPartialAttrs($value, $inputs)
    {
        $result = array();
        $value = (string) $value;
        if (count($inputs))
        {
            for ($i = 0; $i < strlen($value); $i++)
            {
                $ans = $value[$i]; //print $answer."=";
                if (in_array(strlen($value) - $i, $inputs))
                {
                    $result [] = $ans;
                }
            }
        }
        else
        {
            $result[] = $value;
        }
        return $result;
    }

}
