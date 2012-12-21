<?php
$config['Apis']['cloudprint']['path']['host'] = 'www.google.com/cloudprint';
$config['Apis']['cloudprint']['path']['create'] = array(
    'job' => array('submit' => array(
            'printerid',
            'title',
            'capabilities',
            'content',
            'contentType',
            'optional' => array('tag')
    )),
    'printer' => array()
);
$config['Apis']['cloudprint']['path']['read'] = array(
    'job' => array('job' => array()),
    'printer' => array(
        'printer' => array(
            'printerid',
            'optional' => array('printer_connection_status')
        ),
        'capabilities' => array(
            'printerCapabilities' => array('printerid')
        ),
        'search' => array()
    )
);
$config['Apis']['cloudprint']['path']['delete'] = array(
    'job' => array(
        'deletejob' => array('jobid'))
);
?>