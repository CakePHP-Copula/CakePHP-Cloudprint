<?php

App::uses('Job', 'Cloudprint.Model');
App::uses('File', 'Utility');
App::uses('CakeSession', 'Model/Datasource');

/**
 * @package cake
 * @subpackage cake.cake.test.libs
 * @property Job $Job
 */
class JobTestCase extends CakeTestCase {

	public $fixtures = array('plugin.cloudprint.token');

	function setUp() {
		parent::setUp();
		$path = Configure::read('Copula.cloudprint.path');
		if (empty($path)) {
			Configure::load('Cloudprint.paths');
		}
		CakeSession::write('Auth.User.id', '3');
		$this->Job = ClassRegistry::init('Cloudprint.Job');
		$this->Job->useDbConfig = 'cloudprint';
		$this->Job->authorize('3', null, 'cloudprint');
	}

	function testGetJobs() {
		$result = $this->Job->getJobs();
        $this->assertTrue((empty($result) || $result['success'] == true));
	}

	function testAddJobFromFile() {
		$path = CAKE . 'Test' . DS . 'test_app' . DS . 'webroot' . DS . 'theme' . DS . 'test_theme' . DS . 'img' . DS . 'test.jpg';
		$printer_id = "__google__docs";
		$title = "Print Test";
		$return = $this->Job->addJobfromFile($path, $printer_id, $title);
		$this->assertTrue(!empty($return));
	}

	function tearDown() {
		unset($this->Job);
		parent::tearDown();
	}

}

?>