<?php

App::import("Component", "Apis.Oauth");

class CloudprintOauthComponent extends OauthComponent {

    var $name = "CloudprintOauth";
    var $components = array("Session");
    var $_config = array();
    var $_map = array();
    var $controller;
    protected $_oAuthRequestDefaults = array(
        'uri' => array(
            'scheme' => 'https',
        ),
        'method' => 'GET',
        'auth' => array(
            'method' => 'OAuthV2'
        ),
    );

    function startup(&$controller) {
        $id = $this->Session->read("Auth.Vendor.id");
        if (!empty($id)) {
            $isAuthorized = false;
            $name = "Cloudprint";
            $ds = ConnectionManager::getDataSource("cloudprint");
            $this->_config[$name] = $ds->config;
            if ($this->getToken($id)) {
                $isAuthorized = true;
                $ds->config['access_token'] = $this->Session->read('OAuth.' . $name . '.access_token');
            }
            $this->_config[$name]['isAuthorized'] = $isAuthorized;
            $this->Session->write('OAuth.' . $name . '.oauth_consumer_key', $this->_config[$name]['login']);
            $this->Session->write('OAuth.' . $name . '.oauth_consumer_secret', $this->_config[$name]['password']);
            $this->Session->write('OAuth.' . $name . '.isAuthorized', $isAuthorized);
        }
    }

    function initialize(&$controller, $settings = array()) {
        parent::initialize($controller, $settings);
    }

    function getToken($user_id) {
        $token = $this->accessTokenDb($user_id);
        if (!empty($token['Token'])) {
            if (!(strtotime($token['Token']['modified']) >= strtotime("-1 hr"))) {
                $access_token = $this->CloudprintOauth->getRefreshAccess($token['Token']['refresh_token']);
                if (!empty($access_token)) {
                    $token['Token']['access_token'] = $access_token;
                    $Token->save($token);
                }
            }
            $this->Session->write("Oauth.Cloudprint.access_token", $token['Token']['access_token']);
            return true;
        }
        return false;
    }

    function accessTokenDb($user_id) {
        $Token = ClassRegistry::init("Cloudprint.Token");
        return $Token->find(
                        'first', array(
                    'conditions' => array(
                        'user_id' => $user_id
                        )));
    }

    function callback($redirect = null) {
        $oAuthCode = $this->controller->params['url']['code'];
        $grant_type = "authorization_code";
        $access_token = $this->getAccessToken($oAuthCode, $grant_type);
        if ($access_token) {
            return $access_token;
        } else {
            die("Could not get access token");
        }
    }

    function getAccessToken($oAuthCode, $grant_type) {
        $this->_getMap();
        $request = array(
            'method' => 'POST',
            'uri' => array(
                'host' => $this->_map['hosts']['oauth'],
                'path' => $this->_map['oauth']['access'],
                'scheme' => 'https',
                ));
        $body = array(
            'code' => $oAuthCode,
            'client_id' => $this->Session->read('OAuth.Cloudprint.oauth_consumer_key'),
            'client_secret' => $this->Session->read('OAuth.Cloudprint.oauth_consumer_secret'),
            'grant_type' => $grant_type
        );
        $http = new HttpSocket();
        $body = $http->_httpSerialize($body);
        $body .= "&redirect_uri=".$this->_map['callback'];
        $request['body'] = $body;
        $response = $http->request($request);
        if ($http->response['status']['code'] == '200') {
            return json_decode($response, true);
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $refresh_token
     * @return string $access_token
     */
    function getRefreshAccess($refresh_token) {
        $access_token = $this->getAccessToken($refresh_token, 'refresh_token');
        if ($access_token) {
            return $access_token;
        } else {
            die("could not get refresh token");
        }
    }

    function authorize() {
        $this->_getMap();
        $request = array(
            'scheme' => $this->_map['oauth']['scheme'],
            'uri' => array(
                'host' => $this->_map['hosts']['oauth'],
                'path' => $this->_map['oauth']['authorize'],
                'query' => array(
                    'scope' => $this->_map['scope'],
                    'redirect_uri' => $this->_map['callback'],
                    'response_type' => 'code',
                    'client_id' => $this->Session->read('OAuth.Cloudprint.oauth_consumer_key'),
                    'access_type' => 'offline',
                    'approval_prompt' => 'force'
            )),
        );
        $http = new HttpSocket();
        $this->controller->redirect($http->_buildUri($request));
    }

    private function _getMap($dbConfig = null) {
        if (!empty($this->_map)) {
            return;
        }
        if (!$dbConfig) {
            $dbConfig = $this->useDbConfig;
        }
        $datasource = $this->_config[$dbConfig]['datasource'];
        $name = pluginSplit($datasource);
        if (!$this->_map = Configure::read('Apis.' . $name[1])) {
            Configure::load($name[0] . '.' . $name[1]);
            $this->_map = Configure::read('Apis.' . "Cloudprint");
        }
        if (isset($this->_map['oauth']['scheme'])) {
            $this->_oAuthRequestDefaults['uri']['scheme'] = $this->_map['oauth']['scheme'];
        }
    }

}

?>