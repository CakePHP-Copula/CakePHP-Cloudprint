<?php

class DATABASE_CONFIG {

	var $cloudprint = array(
		'datasource' => 'Cloudprint.CloudprintSource',
		'login' => '',
		'password' => '',
		'authMethod' => 'OAuthV2',
		'access_token' => '',
		'refresh_token' => '',
		'expires' => '3600'
	);
	var $cloudprintToken = array(
		'datasource' => 'Copula.RemoteTokenSource',
		'login' => '',
		'password' => '',
		'authMethod' => 'OAuthV2',
		'scheme' => 'https',
		'authorize' => '/o/oauth2/auth',
		'access' => '/o/oauth2/token',
		'host' => 'accounts.google.com',
		'scope' => 'https://www.googleapis.com/auth/cloudprint',
		'callback' => ''
	);

}

?>
