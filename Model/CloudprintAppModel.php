<?php

class CloudprintAppModel extends AppModel {

	public $actsAs = array('Copula.OAuthConsumer' => array('autoFetch' => false));

	public $useDbConfig = 'cloudprint';
}
?>