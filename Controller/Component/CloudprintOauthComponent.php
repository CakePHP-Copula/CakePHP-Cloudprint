<?php

/**
 * @package Cloudprint
 * @subpackage object
 * @property SessionComponent $Session
 */
class CloudprintOauthComponent extends Object {

    var $name = "CloudprintOauth";
    var $components = array("Session");

    /**
     * Set access token for all models using database 'cloudprint'
     *
     * This callback is triggered after the controller's beforeFilter, so we should be auth'd by now.
     * @param type $controller
     */
    function startup(&$controller) {
        if ($this->Session->check('OAuth.Cloudprint.access_token')) {
            foreach ($controller->modelNames as $model) {
                $db = $controller->{$model}->useDbConfig;
                if ($db == 'cloudprint') {
                    $this->setCloudprintAccessToken($this->Session->read('OAuth.Cloudprint.access_token.access_token'));
                }
            }
        }
    }

    function setCloudprintAccessToken($token) {
        $ds = ConnectionManager::getDataSource('cloudprint');
        $ds->config['access_token'] = $token;
    }

    /**
     * This may not be necessary, but the data should probably not be persisted between requests.
     * @param type $controller
     */
    function shutdown(&$controller) {
        if ($this->Session->check('OAuth.Cloudprint.access_token')) {
            $ds = ConnectionManager::getDataSource('cloudprint');
            unset($ds->config['access_token']);
        }
    }

}

?>