<?php

/**
 * This is a stub. Actual functionality will be added later.
 */
class PrintersController extends CloudprintAppController {

	public $uses = array('Cloudprint.Printer');
	public $components = array('Apis.Oauth', 'Auth' => array('authorize' => 'Apis.Oauth'));

	function index() {
		$printers = $this->Printer->getPrinters();
		$this->set('printers', $printers);
	}

	function view($printer_id) {
		if ($printer_id) {
			$printer = $this->Printer->getPrinters($printer_id);
			$this->set('printer', $printer);
		} else {
			$this->redirect(array('controller' => 'printers', 'action' => 'index'));
		}
	}

}

?>