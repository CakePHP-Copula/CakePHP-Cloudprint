<?php

class JobsController extends CloudprintAppController {

    public $name = "Jobs";
    public $uses = array('Cloudprint.Job', 'Cloudprint.Printer', 'Cloudprint.Token');
    public $components = array('Auth', 'Cloudprint.CloudprintOauth');

    function beforeFilter() {
        $this->Session->write('Auth.Vendor.id', '1');
        $this->CloudprintOauth->useDbConfig = "Cloudprint";
        $this->Auth->deny('*');
        $this->Auth->allow('add');
        /* as written, this allows anyone to add a print job as long as the vendor has authorized printing
         * if that is not desired behavior, replace line 14 with something along the lines of:
         *      if($this->params['action'] == "add" && $this->Session->check("Auth.User.id"){
         *          $this->Auth->allow('add');
         *      }
         */
        if ($this->Session->check('Auth.Vendor.id')) {
            if ($this->Session->check('Oauth.Cloudprint.access_token')) {
                $this->Auth->allow('*');
            } else {
                $this->Auth->allow('authorize', 'callback');
                // $this->redirect(array('action' => 'authorize'));
            }
        }
        parent::beforeFilter();
    }

    function index() {
        $jobs = $this->Job->getJobs();
        $this->set('jobs', $jobs);
    }

    function view($jobid) {
        if ($jobid) {
            $job = $this->Job->getJobs($jobid);
            $this->set('job', $job);
        } else {
            $this->redirect(array('controller' => 'jobs', 'action' => 'index'));
        }
    }

    function cancel($jobid) {
        if (!empty($jobid)) {
            if ($this->Job->delete($jobid)) {
                $this->Session->setFlash('Job ' . $jobid . ' cancelled.');
            } else {
                $this->Session->setFlash('Job ' . $jobid . ' could not be cancelled.');
            }
        }
        $this->redirect(array('controller' => 'jobs', 'action' => 'index'));
    }

    function callback() {
        if (!empty($this->params['url']['code'])) {
            xdebug_break();
            $token = $this->CloudprintOauth->callback();
            if ($token) {
                $this->data = array('Token' => array(
                        'user_id' => $this->Session->read('Auth.Vendor.id'),
                        'access_token' => $token['access_token'],
                        'refresh_token' => $token['refresh_token']
                        ));
                $this->Token->save($this->data);
            }
            $this->redirect('/'); # or wherever you want them to go */
        } else {
            $this->Session->setFlash('You chose not to allow access');
        }
    }

    function authorize() {
        $this->CloudprintOauth->authorize();
    }

    function add($resource, $user_id, $title) {
        $token = $this->CloudprintOauth->getToken($user_id);
        if (!empty($token)) {
            $printers = $this->Printer->getPrinters();
            if (!empty($printers[0]['printer_id'])) {
               $response = $this->Job->addJobFromUrl($resource, $printers[0]['printer_id'], $title);
               if($response && $response['success'] == 'true'){
                   //successfully added job
                   return true;
               } else{
                   //did not add job
               }
            }
        } else {
            // vendor has not authorized printing
            return false;
        }
    }

}

?>