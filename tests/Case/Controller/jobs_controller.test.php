<?php
class TestJobController extends JobController{
        var $name = 'Job';

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
class JobControllerTestCase extends CakeTestCase{

    function startTest() {
        $this->Jobs = new TestJobController();
        $this->Jobs->constructClasses();
        $this->Jobs->Component->initialize($this->Jobs);
    }

    function endTest() {
        unset($this->Jobs);
        ClassRegistry::flush();
    }
}
?>