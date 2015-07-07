<?php

use Mockery as m;

class IdeaControllerTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->ideaRepositoryMock = m::mock('App\Contracts\IdeaRepositoryInterface');
		$this->ideaController = new App\IdeaController($this->ideaRepositoryMock);
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('App\IdeaController', $this->ideaController);
	}

}
