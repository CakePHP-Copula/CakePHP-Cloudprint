<?php

$config['Apis']['Cloudprint']['hosts'] = array(
    'oauth' => 'accounts.google.com/o/oauth2',
    'rest' => 'www.google.com/cloudprint'
);
$config['Apis']['Cloudprint']['oauth'] = array(
    'version' => '2.0',
    'scheme' => 'https',
    'authorize' => 'auth',
    'access' => 'token',
    'key' => '286950796224.apps.googleusercontent.com',
    'secret' => '1R6n16IOXoWSGWDseGhpFP3o'
);
$config['Apis']['Cloudprint']['scope'] = "https://www.googleapis.com/auth/cloudprint";
$config['Apis']['Cloudprint']['callback'] = "https://splat.dnsd.me/oauth2callback";
$config['Apis']['Cloudprint']['create'] = array(
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
$config['Apis']['Cloudprint']['read'] = array(
    'job' => array('job' => array()),
    'printer' => array(
        'printer' => array(
            'printerid',
            'optional' => 'printer_connection_status'
        ),
        'capabilities' => array(
            'printerCapabilities' => array('printerid')
        ),
        'search' => array()
    )
);
$config['Apis']['Cloudprint']['delete'] = array(
    'job' => array(
        'deletejob' => array('jobid'))
);
?>