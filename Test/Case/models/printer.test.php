<?php

App::import('Model', 'Cloudprint.Printer');

class PrinterCase extends CakeTestCase {

    function startTest($method) {
        $ds = ConnectionManager::getDataSource('cloudprint');
        $ds->config['access_token'] = "ya29.AHES6ZRf3tiSFvq52G1qGch3nXYjvxAtDf-rwHD13gT2ifBK0h1XVPo";
        $this->Printer = & ClassRegistry::init('Cloudprint.Printer');
    }

    function testGetPrinter() {
        $result = $this->Printer->getPrinters();
        $this->assertEqual($result['success'], true);
    }

    function testGetPrinterInfo() {
        $printerid = '__google__docs';
        $result = $this->Printer->getPrinterInfo($printerid);
        $this->assertEqual($result['success'], true);
    }

}

?>