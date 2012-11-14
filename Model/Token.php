<?php
        App::import('Core', 'HttpSocket');
/**
 *
 * @subpackage model
 * @package Cloudprint
 * @property HttpSocket $Http
 */
class Token extends CloudprintAppModel {

    public $name = "Token";
    public $useDbConfig = "default";
    protected $schema = array(
        'id' => array(
            'type' => 'integer',
            'key' => 'primary'
        ),
        'user_id' => array(
            'type' => 'integer',
            'key' => 'index',
            'null' => false
        ),
        'access_token' => array(
            'type' => 'string',
            'null' => false
        ),
        'refresh_token' => array(
            'type' => 'string',
            'null' => false
        ),
        'modified' => 'dateTime'
    );
    var $Http;
    function __construct($id = false, $table = null, $ds = null) {
        $this->Http = new HttpSocket();
        parent::__construct($id, $table, $ds);
    }
    /**
     * Verifies token validity.
     * @param type $results
     * @param type $primary
     */
    function afterFind($results, $primary = false) {
        if ($primary && $this->findQueryType == "first" && !empty($results['0']['Token']['access_token'])) {
            $token = $results['0']['Token'];
            if ($this->isExpired($token)) {
                $refresh = $this->getRefreshAccess($token);
                if ($refresh) {
                    $results['0']['Token']['access_token'] = $refresh['access_token'];
                    $results['0']['Token']['modified'] = $refresh['modified'];
                }
            }
        }
        return $results;
    }

    /**
     * As written this is more of a "best-guess". The only way we can really be sure that a token is expired is to try to use it.
     * @param array $token array containing an OAuth2 token
     * @return boolean
     */
    function isExpired($token) {
         return ($token['modified'] > strtotime('-1 hr'))? false: true;
    }

    function getAccessToken($oAuthCode, $grant_type) {
        Configure::load('cloudprint.cloudprint');
        $config = Configure::read('Apis.Cloudprint');
        $request = array(
            'method' => 'POST',
            'uri' => array(
                'host' => $config['hosts']['oauth'],
                'path' => $config['oauth']['access'],
                'scheme' => 'https'
                ));
        $body = array(
            'client_id' => $config['oauth']['key'],
            'client_secret' => $config['oauth']['secret'],
            'grant_type' => $grant_type
        );
        if ($grant_type == "refresh_token") {
            $body['refresh_token'] = $oAuthCode;
        } elseif ($grant_type == "authorization_code") {
            $body['code'] = $oAuthCode;
        }
        $body = $this->Http->_httpSerialize($body);
        if ($grant_type == "authorization_code") {
            //append redirect URI to body.
            //it should not be encoded
            $body .= "&redirect_uri=" . $config['callback'];
        }
        $request['body'] = $body;
        $response = $this->Http->request($request);
        if ($this->Http->response['status']['code'] == '200') {
            return json_decode($response, true);
        } else {
            $error = array($request, $response);
           // $this->cakeError('httpSocketError', $error);
        }
    }

    /**
     *
     * @param array $access_token
     * @return array $access_token refreshed access token
     */
    function getRefreshAccess($access_token) {
        $refresh = $this->getAccessToken($access_token['refresh_token'], 'refresh_token');
        if ($refresh) {
            $this->id = $access_token['id'];
            $this->saveField('access_token', $refresh['access_token']);
            $access_token['modified'] = $this->field('modified'); #not strictly necessary, adds a db call.
            $access_token['access_token'] = $refresh['access_token'];
            return $access_token;
        }
    }

    /**
     *
     * @param string $user_id
     * @return array token data
     */
    function getTokenDb($user_id) {
        $result = $this->find(
                'first', array(
            'conditions' => array(
                'user_id' => $user_id
                )));
        return $result['Token'];
    }

    /**
     * Checks for existing token before saving.
     *
     * Technically this is validation logic but since we're not dealing with form data it doesn't make sense to use the built-in logic for that.
     * @param string $user_id
     * @param array $access_token
     */
    function saveTokenDb($user_id, array $access_token) {
        $existing = $this->getTokenDb($user_id);
        if (!empty($existing)) {
            $this->id = $existing['id'];
        }
        $data = array('Token' => array(
                'user_id' => $user_id,
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token']
                ));
        return $this->save($data);
    }

}

?>