<?php

App::uses('Job', 'Cloudprint.Model');
App::uses('File', 'Utility');

/**
 * @package cake
 * @subpackage cake.cake.test.libs
 * @property Job $Job
 */
class JobTestCase extends CakeTestCase {

    function setUp() {
        parent::setUp();
        $ds = ConnectionManager::getDataSource('cloudprint');
        $ds->config['access_token'] = "ya29.AHES6ZT9YLzwNtqG3RXv0VaqCWXgeCmd_7pLOpil-LK0mQ6ZeceO-5o";
        $this->Job = & ClassRegistry::init('Cloudprint.Job');
    }

    function testGetJobs() {
        $result = $this->Job->getJobs();
        $this->assertTrue((empty($result) || $result['success'] == true));
    }

    function testAddJobFromFile() {
        $path = CAKE . 'tests' . DS . 'test_app' . DS . 'webroot' . DS . 'theme' . DS . 'test_theme' . DS . 'img' . DS . 'test.jpg';
        $printer_id = "__google__docs";
        $title = "Print Test";
        $return = $this->Job->addJobfromFile($path, $printer_id, $title);
        $this->assertTrue($return['success']);
    }

    function tearDown() {
        unset($this->Job);
        parent::tearDown();
    }

}

?>