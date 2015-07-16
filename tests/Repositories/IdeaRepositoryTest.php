<?php

use Mockery as m;

class IdeaRepositoryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockQuery = m::mock('SoapBox\Contracts\QueryInterface');
		$this->mockQueryBuilder = m::mock('Illuminate\Database\Query\Builder');
		$this->mockModel = m::mock('Idea');
		$this->ideaRepository = new SoapBox\Repositories\IdeaRepository($this->mockModel);
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\Repositories\IdeaRepository', $this->ideaRepository);
	}

}
