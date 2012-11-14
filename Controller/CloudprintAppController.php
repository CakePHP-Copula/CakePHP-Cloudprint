<?php

/**
 *  App Controller for Cloudprint Plugin
 * @subpackage Cloudprint
 * @property AuthComponent $Auth
 * @property Token $Token
 * @property SessionComponent $Session
 */
class CloudprintAppController extends AppController {

    public $uses = array("Cloudprint.Token");
    public $components = array('Auth', 'Session');

    function beforeFilter() {
        $this->Session->write("Auth.Vendor.id", '1');
        $this->Auth->authorize = 'controller';
        $this->Auth->loginAction = array('controller' => 'oauth', 'action' => 'authorize', 'plugin' => 'cloudprint');
        $this->Auth->autoRedirect = false;
        $this->Auth->userModel = "Vendor";
    }

    function isAuthorized() {
        $id = $this->Session->read("Auth.Vendor.id");
        if ($id) {
            $access_token = $this->Token->getTokenDb($id);
            if (!empty($access_token)) {
                $this->Session->write('OAuth.Cloudprint.access_token', $access_token);
                return true;
            }
        }
        return false;
    }

    function cakeError($method, $messages = array()) {
        if (!class_exists('ErrorHandler')) {
            App::import('Core', 'Error');

            $path = APP . 'plugins' . DS . Inflector::underscore($this->plugin) . DS;

            if (file_exists($path . 'error.php')) {
                include_once ($path . 'error.php');
            } elseif (file_exists($path . 'app_error.php')) {
                include_once ($path . 'app_error.php');
            }
        }
        return parent::cakeError($method, $messages);
    }

}

?>