<?php

App::import('Controller', 'Cloudprint.Printers');

class TestPrintersController extends PrintersController {

    var $name = "Printers";
    var $autoRender = false;

    function redirect($url, $status = null, $exit = true) {
        $this->redirectUrl = $url;
    }

    function render($action = null, $layout = null, $file = null) {
        $this->renderedAction = $action;
    }

    function _stop($status = 0) {
        $this->stopped = $status;
    }

}

class PrintersControllerTestCase extends CakeTestCase {

    function startTest() {
        $this->Printers = new TestPrintersController();
        $this->Printers->constructClasses();
      //  $this->Printers->Component->initialize($this->Printers);
    }

    function testIndex(){
        $this->assertTrue(true);
    }

    function endTest() {
        unset($this->Printers);
        ClassRegistry::flush();
    }

}

?>