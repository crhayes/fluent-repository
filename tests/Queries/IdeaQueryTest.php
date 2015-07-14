<?php

use Mockery as m;
use App\Queries\IdeaQuery;

class IdeaQueryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->ideaQuery = new IdeaQuery();
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('App\Queries\IdeaQuery', $this->ideaQuery);
	}

}
