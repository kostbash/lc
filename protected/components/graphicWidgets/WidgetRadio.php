<?php

class WidgetRadio {

    public $params;
    public $answers;

    function __construct($params) {
        $this->params = $params;
    }

    function draw($answers = null, $numberOfExercise = null) {
        $text = "<div class='dropdown-list'>";
        if ($this->params['a'] !== '' && $answers) {
            $key = key($answers);
            $id = $answers[$key]->id;

            if ($id)
                unset($answers[$key]);

            $list = $this->clearInps();

            $text .= CHtml::radioButtonList("Exercises[$numberOfExercise][answers][$id]", $this->params['s'], $list);
        }
        $text .= "</div>";

        if ($answers) {
            $this->answers = $answers;
        }

        return $text;
    }

    function getAnswersAttrs() {
        $attrs = array();

        if ($this->params['a'] !== '') {
            $attrs[] = $this->params['a'];
        }
        return $attrs;
    }

    function clearInps() {
        $v = array();

        if ($this->params['v']) {
            $arr = explode(',', $this->params['v']);
            if ($arr) {
                foreach ($arr as $key => $row) {
                    $v[$key + 1] = trim($row);
                }
            }
        }
        return array_unique($v);
    }
    
    function drawModalBody() {

        echo '<div class="row">
                    <div class="col-lg-5 col-md-5">
                            <label for="">Название элементов выбора через запятую</label>
                    </div>
                    <div class="col-lg-5 col-md-5">
                            <input type="text" name="v"/>
                    </div>
            </div>
            <div class="row">
                    <div class="col-lg-5 col-md-5">
                            <label for="">Номер выделенного по умолчанию элемента, счет с 1</label>
                    </div>
                    <div class="col-lg-5 col-md-5">
                            <input type="text" name="s"/>
                    </div>
            </div>
            <div class="row">
                    <div class="col-lg-5 col-md-5">
                            <label for="">Номер правильного ответа, счет с 1</label>
                    </div>
                    <div class="col-lg-5 col-md-5">
                            <input type="text" name="a"/>
                    </div>
            </div>
            ';
    }

}
