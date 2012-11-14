<?php

class CloudprintAppModel extends AppModel {
        function cakeError($method, $messages = array()) {
        if (!class_exists('ErrorHandler')) {
            App::import('Core', 'Error');

            $path = APP . 'plugins' . DS . 'cloudprint' . DS;

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