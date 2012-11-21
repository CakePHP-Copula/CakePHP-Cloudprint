<?php
App::uses('ApisSource', 'Apis.Model/Datasource');

Class CloudprintSource extends ApisSource {

    public $options = array(
        'format' => 'json',
        'ps' => '&', // param separator
        'kvs' => '=', // key-value separator
    );
    // Key => Values substitutions in the uri-path right before the request is made. Scans uri-path for :keyname
    public $tokens = array();

 /*   public function __construct($config) {
        Configure::load('cloudprint');
        $config['method'] = 'OAuthV2';
        App::import('Core', 'HttpSocket');
        $http = new HttpSocket(array(
                    'request' => array(
                        'uri' => array(
                            'scheme' => 'https'
                    ))
                ));
        parent::__construct($config, $http);
    }*/

    public function beforeRequest(&$model, $request) {
       $request['header']['x-cloudprint-proxy'] = 'cakephp-cloudprint';
        return $request;
    }
    public function isInterfaceSupported($interface) {
        if($interface == 'listSources'){
            return false;
        }
        parent::isInterfaceSupported($interface);
    }
}

?>
