<?php

App::uses('Printer', 'Cloudprint.Model');
App::uses('CakeSession', 'Model/Datasource');

class PrinterCase extends CakeTestCase {

	public $fixtures = array('plugin.cloudprint.token');

	function setUp() {
		parent::setUp();
		$path = Configure::read('Copula.cloudprint.path');
		if (empty($path)) {
			Configure::load('Cloudprint.paths');
		}
		CakeSession::write('Auth.User.id', '3');
		$this->Printer = ClassRegistry::init('Cloudprint.Printer');
		$this->Printer->useDbConfig = 'cloudprint';
		$this->Printer->authorize('3', null, 'cloudprint');
	}

	function testGetPrinter() {
		$result = $this->Printer->getPrinters();
		$this->assertEquals(true, $result['success']);
	}

	function testGetPrinterInfo() {
		$printerid = '__google__docs';
		$result = $this->Printer->getPrinterInfo($printerid);
		$this->assertEquals(true, $result['success']);
	}

	function tearDown() {
		unset($this->Printer);
		parent::tearDown();
	}

}

?>