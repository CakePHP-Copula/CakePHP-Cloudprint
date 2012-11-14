<?php

App::import('Controller', 'Cloudprint.Jobs');

class TestJobsController extends JobsController {

    var $name = 'Jobs';
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

class JobsControllerTestCase extends CakeTestCase {

    function startTest($method) {
        $this->Jobs = new TestJobsController();
        $this->Jobs->constructClasses();
        $this->Jobs->Component->initialize($this->Jobs);
        App::import('Component', 'Session');
        Mock::generate('SessionComponent');
        $this->Jobs->Session = new MockSessionComponent();
        $this->Jobs->Session->setReturnValue('read', 1, array('Auth.User.id'));
    }


    function testCallback() {

    }

    function testAddJob() {

    }

    function testGetPrinters() {

    }

    function endTest() {
        unset($this->Jobs);
        ClassRegistry::flush();
    }

}

?>