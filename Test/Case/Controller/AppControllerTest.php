<?php

App::import('Controller', 'Cloudprint.App');

class TestCloudprintAppController extends CloudprintAppController {

    var $name = "App";
    var $autoRender = false;

    function testNotAuthed() {
        //this should trigger Auth
    }

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

/**
 * @package cake
 * @subpackage cake.cake.test.libs
 * @propert TestCloudprintAppController $App
 */
class CloudprintAppControllerTestCase extends CakeTestCase {

    var $fixtures = array('plugin.cloudprint.token');

    function startTest() {
        $this->App = new TestCloudprintAppController();
        $this->App->constructClasses();
        $this->App->Component->initialize($this->App);
        App::import('Component', 'Session');
        Mock::generate('SessionComponent');
        $this->App->Session = new MockSessionComponent();
    }

    function testIsAuthorized() {
        $this->App->Session->setReturnValue('read', 1, array('Auth.Vendor.id'));
        $this->assertTrue($this->App->isAuthorized());
    }

    function testNotAuthorized() {
        $this->App->Session->setReturnValue('read', 99, array('Auth.Vendor.id'));
        $this->assertFalse($this->App->isAuthorized());
    }

    function endTest() {
        unset($this->App);
        ClassRegistry::flush();
    }

}

?>