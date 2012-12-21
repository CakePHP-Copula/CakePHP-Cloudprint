<?php

App::uses('AppController', 'Controller');
App::uses('CloudprintAppController', 'Cloudprint.Controller');

class AllCloudprintTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All Cloudprint Plugin Tests');
		$suite->addTestDirectoryRecursive(APP . 'Plugin' . DS . 'Cloudprint' . DS . 'Test' . DS . 'Case');
		return $suite;
	}

}
?>