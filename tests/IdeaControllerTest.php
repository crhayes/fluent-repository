<?php

use Mockery as m;

class IdeaControllerTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->ideaRepositoryMock = m::mock('SoapBox\Contracts\IdeaRepositoryInterface');
		$this->ideaController = new SoapBox\IdeaController($this->ideaRepositoryMock);
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\IdeaController', $this->ideaController);
	}

}
