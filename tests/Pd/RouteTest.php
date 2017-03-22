<?php
namespace tests;

use \Pd\Request;
use Pd\Route;

class RouteTest extends TestCase {

    public function testConfigMapWithConfig() {
        $request = $this->getMockBuilder(Request::class)
            ->getMock();
        $request->method("uri")->willReturn("/test/info");

        $configArray = array(
            "/test/info" => '\Hello\ClassInfo::soft'
        );

        $route = new Route();
        $route->setConfigArray($configArray);
        $route->controllerRelationMap($request);

        $this->assertEquals($route->getControllerClassString(), "\\Hello\\ClassInfo");
        $this->assertEquals($route->getMethodString(),"soft");
    }
}