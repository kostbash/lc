<?php

class GraphicWidget
{

    const LETTER_WIDTH = 14;
    
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
                    $ans = CHtml::textField("Exercises[$numberOfExercise][answers][" . $answers[GraphicWidgets::$key]->id . "]", '', array('style' => "width:" . (self::LETTER_WIDTH) . "px;", "key" => GraphicWidgets::$key));
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
            $result = CHtml::textField("Exercises[$numberOfExercise][answers][" . $answers[GraphicWidgets::$key]->id . "]", '', array('style' => (strlen($value)>1?"letter-spacing:3px;":"")."width:" . (mb_strlen($answers[GraphicWidgets::$key]->answer, 'UTF-8') * self::LETTER_WIDTH - 1) . "px;", "key" => GraphicWidgets::$key, 'onblur'=>'$(this).setCursorPosition(0);'));
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
