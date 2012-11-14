<?php

/**
 *
 * @subpackage model
 * @package Cloudprint
 */
class Job extends CloudprintAppModel {

    public $name = 'Job';
    public $useDbConfig = 'cloudprint';
    public $useTable = 'job';
    public $primaryKey = 'id';
    var $_schema = array(
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

    function onError() {
        if (stristr($this->response, 'Error 404')) {
            // $this->cakeError();
        }
    }

    /*
     * Returns array of all jobs.
     *
     * ApisSource doesn't handle queries with *only* optional conditions well.
     * @return array One or more jobs
     */

    function getJobs() {
        $query = array('fields' => 'job');
        $jobs = $this->find('all', $query);
        return $jobs;
    }

    /* Adds page from local website to print queue
     * Creates document in app/tmp with filename of time() + $title
     * @param string $url Cake-local url of document to be printed
     * @param string $printerid
     * @param string $title Title of document
     * @param array  $capabilities Capabilities can be discovered using getPrinterInfo(). Can be used to set double sided or multiple copies.
     * @param string $tags A string of tags separated by spaces. Documents can be searched for by tag.
     */

    function addJobfromURL($url, $printerid, $title, $capabilities= null, $tags = null) {
        $document = $this->requestAction($url, array('return'));
        $resource = new File(TMP . DS . time() . $title, true);
        $resource->write($document);
        return $this->addJob($resource, $printerid, $title, $capabilities, $tags);
    }

    function addJobfromFile($path, $printerid, $title, $capabilities = null, $tags = null) {
        $resource = new File($path);
        if ($resource->exists()) {
            return $this->addJob($resource, $printerid, $title, $capabilities, $tags);
        }
    }

    /* shamelessly cribbed from CakePHP 2.0
     * @param File $file document to be tested
     */

    function getMime(File &$file) {
        if (!$file->exists()) {
            return false;
        }
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            list ($type, $charset) = explode(';', finfo_file($finfo, $file->pwd()));
            return $type;
        } elseif (function_exists('mime_content_type')) {
            return mime_content_type($file->pwd());
        }
        return false;
    }

    /*
     * @param File $resource the file to be printed
     * @param string $printerid
     * @param string $title
     * @param array $capabilities
     * @param string $tags
     */

    private function addJob(File &$resource, $printerid, $title, $capabilities = null, $tags = null) {
        $capabilities = (empty($capabilities)) ? "{[]}" : $capabilities;
        $acceptedContentTypes = array(
            'application/pdf',
            'image/jpeg',
            'image/png'
        );
        $mime = $this->getMime($resource);
        if (in_array($mime, $acceptedContentTypes)) {
            $job = array(
                'Job' => array(
                    'printerid' => $printerid,
                    'title' => $title,
                    'capabilities' => $capabilities,
                    'content' => "data:" . $mime . ";base64," . base64_encode($resource->read()),
                    'contentType' => $mime
                    ));
            if (!empty($tags)) {
                $job['Job']['tag'] = $tags;
            }
            $this->request['method'] = 'POST';
            $this->save($job);
            return $this->response;
        } else {
            return false;
        }
    }

}

?>