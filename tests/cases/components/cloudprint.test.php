<?php

App::import('Component', 'Session');
App::import('Component', 'Cloudprint.CloudprintOauth');

class OauthTestController extends Controller {

    var $name = "OauthTest";
    var $uses = null;
    var $components = array('Session', 'CloudprintOauth');

}

class CloudprintOauthTest extends CakeTestCase {

    function startTest() {
        $this->OauthTest = new OauthTestController();
        $this->OauthTest->constructClasses();
        $this->OauthTest->Component->initialize($this->OauthTest);
    }

    function endTest() {
        unset($this->OauthTest);
        ClassRegistry::flush();
    }

}

?>