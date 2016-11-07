<?php
namespace tests;

use \Pd\Response;

class ResponseTest extends TestCase {

    public function testString() {
        $response = new Response();
        $response->setType(Response::TYPE_STRING);
        $response->setData("Hello Response!");
        $response->make();
        $this->assertEquals($response->__toString(), "Hello Response!");
    }

}