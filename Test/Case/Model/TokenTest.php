<?php

App::uses('Token', 'Cloudprint.Model');
App::uses('HttpResponse', 'Network/Http');
App::uses('HttpSocket', 'Network/Http');

/**
 * @package cake
 * @subpackage cake.test
 * @property Token $Token
 */
class TokenTestCase extends CakeTestCase {

    var $fixtures = array('plugin.cloudprint.token');
    var $results = array(array(
            'Token' => array(
                'id' => '4',
                'user_id' => '1',
                'access_token' => 'ya29.AHES6ZTopEd2PaRCaLZDd0B9TKNqdt857DYrlC-Welo9d84LaElzAg',
                'refresh_token' => '1/jr6xd0f83uXDh-sBE3eO_lo8qMr11pOQXalzfTAYXGk',
                'modified' => '2012-11-07 23:10:18'
        )));

    function setUp() {
        parent::setUp();
        $this->Token = ClassRegistry::init('Cloudprint.Token');
    }

    function testGetTokenDb() {
        $this->Token->Behaviors->disable('AccessToken');
        $token = $this->Token->getTokenDb('1');
        $this->assertTrue(!empty($token['access_token']));
        ;
        $noToken = $this->Token->getTokenDb('4');
        $this->assertTrue(empty($noToken['access_token']));
    }

    function testSaveTokenDb() {
        $this->Token->Behaviors->enable('AccessToken');
        $return = $this->Token->saveTokenDb('12', $this->results[0]['Token']);
        $this->assertTrue(!empty($return['Token']));
    }

    function tearDown() {
        unset($this->Token);
        ClassRegistry::flush();
        parent::tearDown();
    }

}

?>