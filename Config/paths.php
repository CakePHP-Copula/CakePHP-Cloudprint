<?php

$config['Copula']['cloudprint']['path']['create'] = array(
	'job' => array(
		'path' => 'submit',
		'required' => array(
			'printerid',
			'title',
			'capabilities',
			'content',
			'contentType'),
		'optional' => array('tag')
	),
	'printer' => array()
);
$config['Copula']['cloudprint']['path']['read'] = array(
	'job' => array(
		'path' => 'jobs',
		'required' => array(),
		'optional' => array('printerid')
	),
	'printer' => array(
		'path' => 'printer',
		'required' => array('printerid'),
		'optional' => array('printer_connection_status')),
	'search' => array(
		'path' => 'search',
		'required' => array(),
		'optional' => array('q', 'connection_status')
	)
);
$config['Copula']['cloudprint']['path']['delete'] = array(
	'job' => array(
		'path' => 'deletejob',
		'required' => array('jobid')
	)
);
?>