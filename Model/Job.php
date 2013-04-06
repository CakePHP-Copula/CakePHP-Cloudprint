<?php

/**
 *
 * @subpackage model
 * @package Cloudprint
 */
class Job extends CloudprintAppModel {

	public $name = 'Job';
	public $useTable = 'job';
	public $primaryKey = 'id';

	//this should probably be limited to save fields
	public $schema = array(
		"id" => array(
			'type' => 'string',
			'length' => '255'
		),
		"title" => array(
			'type' => 'string'),
		"printerid" => array('type' => 'string'),
		"capabilities" => array('type' => 'string'),
		"content" => array('type' => 'string'),
		"contentType" => array('type' => 'string'),
		'tags' => array('type' => 'string'),
		'fileURL' => array('type' => 'string'),
		'ticketURL' => array('type' => 'string'),
		'createTime' => array('type' => 'date'),
		'updateTime' => array('type' => 'date'),
		'status' => array('type' => 'string'),
		'errorCode' => array('type' => 'string'),
		'message' => array('type' => 'string'),
	);
	var $validate = array(
		'printer_id' => 'notEmpty',
		'title' => 'notEmpty',
		'capabilities' => 'notEmpty',
		'content' => array(
			/*'mime' => array(
				'rule' => array('mimeType', array(
						'application/pdf',
						'image/jpeg',
						'image/png'
				)),
				'message' => 'Content must be PDF, JPEG, or PNG or a URL',
				'allowEmpty' => false,
				'last' => false
			),
			'website' => array(
				'rule' => 'validateRemote',
				'message' => 'Content must be web-accessible PDF, JPEG, or PNG',
				'last' => false
			)*/
		),
		'contentType' => array(
			'type' => array(
				'rule' => array('inList', array('application/pdf',
						'image/jpeg',
						'image/png')),
				'message' => 'Content type needs to be a valid mimetype.',
				'allowEmpty' => false
			)
		),
		'tag' => 'alphaNumeric'
	);

	function validateRemote($url) {
		if (Validation::url($url)) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			curl_exec($ch);
			$mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			return in_array($mime, array('application/pdf', 'image/jpeg', 'image/png'));
		} else {
			return false;
		}
	}

	function onError() {
		if ($this->response instanceof HttpSocketResponse && $this->response->code == 403) {
			if ($this->authorize(AuthComponent::user('id'))) {
				throw new CakeException(__('Api %s encountered an authentication error, please try again.', $this->useDbConfig), '403');
			} else {
				throw new CakeException(__('Api %s encountered an unexpected error.', $this->useDbConfig), '403');
			}
		}
	}

	/*
	 * Returns information about one or more jobs.
	 *
	 * @todo ApisSource doesn't handle queries with *only* optional conditions well.
	 * @return array One or more jobs
	 */

	function getJobs($jobid = null) {
		$query = array('section' => 'job');
		if ($jobid) {
			$query['jobid'] = $jobid;
		}
		$jobs = $this->find('all', $query);
		return $jobs;
	}

	/* Adds page from local website to print queue
	 * Creates document in app/tmp with filename of time() + $title
	 *
	 * I considered rewriting this to be able to fetch documents that are on the public internet. I have decided that this plugin is slow enough without blocking execution to fetch remote files.
	 * If you really need the functionality, replace $this->requestAction with some sort of curl call.
	 * @param string $url Cake-local url of document to be printed
	 * @param string $printerid
	 * @param string $title Title of document
	 * @param array  $capabilities Capabilities can be discovered using getPrinterInfo(). Can be used to set double sided or multiple copies.
	 * @param string $tags A string of tags separated by spaces. Documents can be searched for by tag.
	 */

	function addJobfromURL($url, $printerid, $title, $capabilities = null, $tags = null) {
		$document = $this->requestAction($url, array('return'));
		$resource = new File(TMP . DS . time() . $title, true);
		$resource->write($document);
		return $this->addJob($resource, $printerid, $title, $capabilities, $tags);
	}

	function addJobfromFile($path, $printerid, $title, $capabilities = null, $tags = null) {
		$resource = new File($path);
		if ($resource->exists()) {
			return $this->addJob($resource, $printerid, $title, $capabilities, $tags);
		} else {
			throw new NotFoundException("The file to be printed was not found on the server", 404);
		}
	}

	/*
	 * @param File $resource the file to be printed
	 * @param string $printerid
	 * @param string $title
	 * @param array $capabilities
	 * @param string $tags
	 */

	private function addJob(File &$resource, $printerid, $title, $capabilities = null, $tag = null) {
		$capabilities = (empty($capabilities)) ? "{[]}" : $capabilities;
		$mime = $resource->mime();
		$job = array(
			'Job' => array(
				'printerid' => $printerid,
				'title' => $title,
				'capabilities' => $capabilities,
				'content' => "data:" . $mime . ";base64," . base64_encode($resource->read()),
				'contentType' => $mime
				));
		if (!empty($tag)) {
			$job['Job']['tag'] = $tag;
		}
		$response = $this->save($job);
		return $response;
	}

}

?>