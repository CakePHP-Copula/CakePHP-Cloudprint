<?php

/**
 *  App Controller for Cloudprint Plugin
 * @subpackage Cloudprint
 * @property AuthComponent $Auth
 * @property Token $Token
 * @property SessionComponent $Session
 */
class CloudprintAppController extends AppController {

    public $components = array('Auth', 'Session');

    function beforeFilter() {
       // $this->Session->write("Auth.User.id", '1'); #for testing
        $this->Auth->authorize = 'Cloudprint.Oauth';
        $this->Auth->unauthorizedRedirect = false;
    }

}

?>