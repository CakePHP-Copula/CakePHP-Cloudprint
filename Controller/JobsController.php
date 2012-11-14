<?php

/**
 *
 * @subpackage controller
 * @package Cloudprint
 * @property Job $Job
 * @property CloudprintOauthComponent $CloudprintOauth
 * @property PdfizePdfComponent $Pdf
 */
class JobsController extends CloudprintAppController {

    public $name = "Jobs";
    public $uses = array('Cloudprint.Job', 'Cloudprint.Printer');
    public $components = array('Cloudprint.CloudprintOauth', 'Pdfize.Pdf' => array(
            'actions' => array('pdftest'),
            'size' => 'a7',
            'orientation' => 'portrait',
            ));

    function beforeFilter() {
        //allow any logged in users to add print jobs
        if ($this->Auth->user()) {
            $this->Auth->allow('add');
        }
        $this->Auth->allow('pdftest');
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

    function pdftest() {
        /*
         * This function exists only to demonstrate how to set up the PDF component. It uses the default layout in plugins/pdfize/views/layouts/pdf
         * If you create the file app/views/layouts/pdf.ctp it will use that instead.
         *
         */
    }

    function add($resource, $title, $user_id = null) {
        $id = ($user_id) ? $user_id : $this->Session->read('Auth.Vendor.id');
        $token = $this->Token->getTokenDb($id);
        if ($token) {
            $this->CloudprintOauth->setCloudprintAccessToken($token['access_token']);
            $printers = $this->Printer->getPrinters();
            if (!empty($printers)) {
                $printer_id = $printers['0']['id'];
                $response = $this->Job->addJobFromUrl($resource, $printer_id, $title);
                if ($response && $response['success'] == 'true') {
                    return true;
                } else {
                    $job = array(
                        'resource' => $resource,
                        'title' => $title,
                        'vendor_id' => $user_id,
                        'user_id' => $id,
                        'response' => $response
                    );
                    $this->cakeError('printJobError', $job);
                }
            } else {
                //vendor has no active printers
            }
        } else {
            // vendor has not authorized printing
            return false;
        }
    }

}

?>