<?php

App::import('Model', 'Cloudprint.Job');
App::import('Core', 'File');
/**
 * @package cake
 * @subpackage cake.cake.test.libs
 * @property Job $Job
 */
class JobTestCase extends CakeTestCase {

    function startTest($method) {
        $ds = ConnectionManager::getDataSource('cloudprint');
        $ds->config['access_token'] = "ya29.AHES6ZT9YLzwNtqG3RXv0VaqCWXgeCmd_7pLOpil-LK0mQ6ZeceO-5o";
        $this->Job = & ClassRegistry::init('Cloudprint.Job');
    }

    function testGetJobs() {
        $result = $this->Job->getJobs();
        $this->assertTrue((empty($result) || $result['success'] == true));
    }

    function testAddJobFromFile() {
        $path = CAKE . 'tests' . DS . 'test_app' . DS . 'webroot' . DS . 'theme' . DS . 'test_theme' . DS . 'img' . DS . 'test.jpg';
        $printer_id = "__google__docs";
        $title = "Print Test";
        $return = $this->Job->addJobfromFile($path, $printer_id, $title);
        $this->assertTrue($return['success']);
    }

    /**
     * also cribbed from Cake 2
     */
    function testMime() {
        $this->skipIf(!function_exists('finfo_open') && !function_exists('mime_content_type'), 'Not able to read mime type');
        $path = CAKE . 'tests' . DS . 'test_app' . DS . 'webroot' . DS . 'theme' . DS . 'test_theme' . DS . 'img' . DS . 'cake.power.gif';
        $file = new File($path);
        $expected = 'image/gif';
        if (function_exists('mime_content_type') && false === mime_content_type($file->pwd())) {
            $expected = false;
        }
        $this->assertEqual($expected, $this->Job->getMime($file));
    }

    function testFileNotFound() {
//create file handle
        $file = new File(TMP . 'noexist.html', false);
    }

}

?>