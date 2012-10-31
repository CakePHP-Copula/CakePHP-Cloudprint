<?php

class Printer extends CloudprintAppModel {

    public $name = "Printer";
    public $useDbConfig = "cloudprint";
    public $useTable = "printer";
    public $primaryKey = "id";

    function getPrinters($status = null) {
        $printers = $this->find('all', array(
            'fields' => 'search',
            'connection_status' => $status,
                ));
        return $printers;
    }
    function getPrinterInfo($printer_id, $status = null){
        $info = $this->find('all', array(
            'fields' => 'printer',
            'printer_id' => $printer_id,
            'printer_connection_status' => $status
        ));
        return $info;
    }
}

?>