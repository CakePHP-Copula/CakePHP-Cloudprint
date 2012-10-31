<?php
$config['Apis']['Cloudprint']['hosts'] = array(
    'oauth' => 'accounts.google.com/o/oauth2',
    'rest' => 'www.google.com/cloudprint'
);
$config['Apis']['Cloudprint']['oauth'] = array(
    'version' => '2.0',
    'scheme' => 'https',
    'authorize' => 'auth',
    'access' => 'token'
);
$config['Apis']['Cloudprint']['scope'] = "https://www.googleapis.com/auth/cloudprint";
$config['Apis']['Cloudprint']['callback'] = "callback_uri_goes_here";
$config['Apis']['Cloudprint']['create'] = array(
    'job' => array('submit' => array(
            'printerid',
            'title',
            'capabilities' => array(),
            'content',
            'contentType',
            'tag'
    )),
    'printer' => array()
);
$config['Apis']['Cloudprint']['read'] = array(
    'job' => array('job' => array(
            'optional' => 'printerid'
    )),
    'printer' => array(
        'printer' => array(
            'printerid',
            'optional' => 'printer_connection_status'
        ),
        'capabilities' => array(
            'printerCapabilities' => array('printerid')
        ),
        'search' => array(
            'optional' => array(
                'q', 'connection_status'))
    )
);
$config['Apis']['Cloudprint']['delete'] = array(
    'job' => array(
        'deletejob' => array('jobid'))
);
?>