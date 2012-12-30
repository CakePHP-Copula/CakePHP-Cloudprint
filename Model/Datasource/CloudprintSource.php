<?php
App::uses('ApisSource', 'Copula.Model/Datasource');

Class CloudprintSource extends ApisSource {

    public function beforeRequest(Model $model) {
       $model->request['header']['x-cloudprint-proxy'] = 'cakephp-cloudprint';
        return $model->request;
    }
    public function isInterfaceSupported($interface) {
        if($interface == 'listSources'){
            return false;
        }
        parent::isInterfaceSupported($interface);
    }
}

?>
