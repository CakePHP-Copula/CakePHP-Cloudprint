<?php

Class Cloudprint extends ApisSource {

    public $options = array(
        'format' => 'json',
        'ps' => '&', // param separator
        'kvs' => '=', // key-value separator
    );
    // Key => Values substitutions in the uri-path right before the request is made. Scans uri-path for :keyname
    public $tokens = array();


    public function __construct($config) {
        $config['access_token'] = $_SESSION['OAuth']['Cloudprint']['access_token'];
        $config['method'] = 'OAuthV2';
        App::import('Core', 'HttpSocket');
        $http = new HttpSocket(array(
            'request' => array(
                'scheme' => 'https'
            )
        ));
        parent::__construct($config, $http);
    }
    public function addOauthV2(&$model, $request) {
        $request['auth']['method'] = "OAuth " . $this->config['access_token'];
        return $request;
    }

    public function beforeRequest(&$model, $request) {
        //  $request['header']['x-li-format'] = $this->options['format'];
        return $request;
    }

}

?>
