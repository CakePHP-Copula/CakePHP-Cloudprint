<?php

App::import('Model', 'Cloudprint.Token');

/**
 * @package cake
 * @subpackage cake.cake.tests.lib
 * @property Token $Token
 */
class TokenCase extends CakeTestCase {

    var $fixtures = array('plugin.cloudprint.token');
    var $results = array(array(
        'Token' => array(
                'id' => '4',
                'user_id' => '1',
                'access_token' => 'ya29.AHES6ZTopEd2PaRCaLZDd0B9TKNqdt857DYrlC-Welo9d84LaElzAg',
                'refresh_token' => '1/jr6xd0f83uXDh-sBE3eO_lo8qMr11pOQXalzfTAYXGk',
                'modified' => '2012-11-07 23:10:18'
        )));

    function startTest() {
        $this->Token = & ClassRegistry::init('Token');
        Mock::generatePartial('HttpSocket', 'MockHttpSocket', array('request'));
        $this->Token->Http = new MockHttpSocket();
        $this->Token->Http->response['status']['code'] = '200';
        $returnValue = json_encode(array(
            'access_token' => 'ya29.AHES6ZTopEd2PaRCaLZDd0B9TKNqdt857DYrlC-Welo9d84LaElzAg',
            'refresh_token' => '1/jr6xd0f83uXDh-sBE3eO_lo8qMr11pOQXalzfTAYXGk'
                ));
        $this->Token->Http->setReturnValue('request', $returnValue);
    }

    function testGetToken() {
        $token = $this->Token->getTokenDb('1');
        $this->assertTrue(!empty($token['access_token']));;
        $noToken = $this->Token->getTokenDb('4');
        $this->assertTrue(empty($noToken['access_token']));
    }

    function testAfterFind() {
        $this->Token->findQueryType = "first";
        $results = $this->Token->afterFind($this->results, true);

    }

    function testIsExpired() {
        $sooner = strtotime('-5 min');
        $later = strtotime('2012-11-06 23:10:18');
        $token = array('modified' => $later);
        $this->assertTrue($this->Token->isExpired($token));
        $token['modified'] = $sooner;
        $this->assertFalse($this->Token->isExpired($token));
    }

    function endTest() {
        unset($this->Token);
        ClassRegistry::flush();
    }
  
}

?>