<?php

/**
 *
 * @subpackage controller
 * @package Cloudprint
 * @property Job $Job
 * @property OauthComponent $Oauth
 * @property PdfizePdfComponent $Pdf
 */
class JobsController extends CloudprintAppController {

	public $name = "Jobs";

	public $uses = array('Cloudprint.Job', 'Cloudprint.Printer');

	/* public $components = array(
		'Pdfize.Pdf' => array(
			'actions' => array('pdftest'),
			'size' => 'a7',
			'orientation' => 'portrait',
		)
		);*/
	public $Apis = array('cloudprint' => array('store' => 'Db'));

	public function beforeFilter() {
		//allow any logged in users to add print jobs
		if ($this->Auth->loggedIn()) {
			$this->Auth->allow('add');
		}
		$this->Auth->allow('pdftest', 'callback');
		parent::beforeFilter();
	}

	public function index() {
		$jobs = $this->Job->getJobs();
		$this->set('jobs', $jobs);
	}

	public function view($jobid) {
		if ($jobid) {
			$job = $this->Job->getJobs($jobid);
			$this->set('job', $job);
		} else {
			$this->redirect(array('controller' => 'jobs', 'action' => 'index'));
		}
	}

	public function cancel($jobid) {
		if (!empty($jobid)) {
			if ($this->Job->delete($jobid)) {
				$this->Session->setFlash('Job ' . $jobid . ' cancelled.');
			} else {
				$this->Session->setFlash('Job ' . $jobid . ' could not be cancelled.');
			}
		}
		$this->redirect(array('controller' => 'jobs', 'action' => 'index'));
	}

	public function pdftest() {
		/*
		 * This function exists only to demonstrate how to set up the PDF component. It uses the default layout in plugins/pdfize/views/layouts/pdf
		 * If you create the file app/views/layouts/pdf.ctp it will use that instead.
		 *
		 */
	}

	public function add($resource, $title, $user_id = null) {
		$id = ($user_id) ? $user_id : AuthComponent::user('id');
		$token = $this->Token->getTokenDb('cloudprint', $id);
		if ($token) {
			OauthCredentials::setAccessToken('cloudprint', $token['access_token']);
			$printers = $this->Printer->getPrinters();
			if (!empty($printers)) {
				$printer_id = $printers['0']['id'];
				if ($printer_id == '__google__docs') {
					$printer_id = $printers['1']['id'];
				}
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
					throw new CakeException('Error with Print Job. Details: ' . serialize($job));
				}
			} else {
				//user has no active printers
			}
		} else {
			// user has not authorized printing
			return false;
		}
	}

	public function callback() {
		$this->Oauth->callback('cloudprint');
		//the following will only be called if there isn't already an Oauth.redirect in Session.
		$this->redirect('/');
	}

}

?>