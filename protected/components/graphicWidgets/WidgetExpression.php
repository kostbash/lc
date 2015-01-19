<?php

class WidgetExpression
{
    public $params;
    public $answers;
    public $inpsValues;
    public $inpName = 'finp';
    
    function __construct($params) {
        $this->params = $params;
        $this->clearInpsValues();
    }
    
    function draw($answers=null, $numberOfExercise=null)
    {
        $this->addInps($answers, $numberOfExercise);
        $text = "<div class='graphic-widget'>";
            $text .= "<div class='expression hideMathJax tex2jax_process'>";
                if($this->params['e'])
                {
                    $text .= "\[" . $this->params['e'] . "\]";
                }
            $text .= "</div>";
        $text .= "</div>";
        return $text;
    }
    
    // заменяет на инпуты
    function addInps($answers, $numberOfExercise)
    {
        $count = mb_substr_count($this->params['e'], $this->inpName, "UTF-8");
        $i = 0;
        while($count && $answers)
        {
            if($this->inpsValues[$i]!='')
            {
                $key = key($answers);
                $id = $answers[$key]->id;
                $width = (int) mb_strlen($answers[$key]->answer, 'UTF-8');
                $len = mb_strlen($this->inpName, 'UTF-8');
                $begin = mb_strstr($this->params['e'], $this->inpName, true, 'UTF-8');
                $end = mb_substr(mb_strstr($this->params['e'], $this->inpName, false, 'UTF-8'), $len);
                $this->params['e'] = $begin . "\FormInput[$width][][]{Exercises[$numberOfExercise][answers][$id]}" . $end;
                
                if($id)
                {
                    unset($answers[$key]);
                }
            }
            else
            {
                break;
            }
            --$count;
            ++$i;
        }
        
        if($answers)
        {
            $this->answers = $answers;
        }
    }
    
    function getAnswersAttrs()
    {
        $attrs = array();
        $count = mb_substr_count($this->params['e'], $this->inpName, "UTF-8");
        $i = 0;
        while($count)
        {
            if($this->inpsValues[$i]!='')
            {
                $attrs[] = $this->inpsValues[$i];
            }
            else
            {
                break;
            }
            --$count;
            ++$i;
        }
        return $attrs;
    }
    
    function clearInpsValues()
    {
        $inps = array();
        if($this->params['a'])
        {
            $this->params['a'];
            $dirtyInps = explode(',', $this->params['a']);
            if($dirtyInps)
            {
                foreach($dirtyInps as $dirtyInp)
                {
                    $inps[] = trim($dirtyInp);
                }
            }
        }
        $this->inpsValues = $inps;
    }
}

