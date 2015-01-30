<?php

class ButtonsWidget extends CWidget {

    public function run() {
        $gw = new GraphicWidgets();

        $this->render('buttonswidget', array(
            'widgets' => $gw->getAllWidgetsName(),
            'gw' => $gw,
        ));
    }

}
