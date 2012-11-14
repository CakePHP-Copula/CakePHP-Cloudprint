<?php

class AppError extends ErrorHandler {

    function _outputMessage($template) {
        $this->controller->viewPath = '..' . DS . 'plugins' . DS . basename(dirname(__FILE__)) . DS . 'views' . DS . 'errors';
        parent::_outputMessage($template);
    }

    function httpSocketError($params) {
        echo debug($params);
        die();
    }
    function printJobError($params){
        $this->controller->set('job', $params);
    }
}

?>