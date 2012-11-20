<?php

/**
 *
 * @subpackage model
 * @package Cloudprint
 */
class Token extends CloudprintAppModel {

    public $name = "Token";
    public $useDbConfig = "default";
    protected $_schema = array(
        'id' => array(
            'type' => 'integer',
            'key' => 'primary',
            'length' => '11'
        ),
        'user_id' => array(
            'type' => 'integer',
            'key' => 'index',
            'null' => false,
            'length' => '11'
        ),
        'access_token' => array(
            'type' => 'string',
            'null' => false
        ),
        'refresh_token' => array(
            'type' => 'string',
            'null' => false
        ),
        'modified' => 'dateTime',
        'api' => array(
            'type' => 'string',
            'null' => false
        )
    );
    var $validate = array(
        'id' => array(),
        'user_id' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'user_id must be numeric'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'user_id must be unique'
            )
        ),
        'api' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'message' => 'API names must be alphanumeric. In point of fact they should probably be camelcased singular.'
            )
        )
    );
    var $actsAs = array(
        'Cloudprint.AccessToken' => array(
            'Api' => 'Cloudprint',
            'expires' => '3600'
        )
    );

    /**
     * Convenience method for retrieving tokens
     * @param string $user_id
     * @return array token data
     */
    function getTokenDb($user_id) {
        $result = $this->findByUserId($user_id);
        $result = (empty($result)) ? $result : $result['Token'];
        return $result;
    }

    /**
     * Convenience method for saving.
     *
     * Data is munged with behavior callbacks afterwards.
     * @param string $user_id
     * @param array $access_token
     */
    function saveTokenDb($user_id, array $access_token) {
        $data = array('Token' => array(
                'user_id' => $user_id,
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token']
                ));
        return $this->save($data);
    }

}

?>