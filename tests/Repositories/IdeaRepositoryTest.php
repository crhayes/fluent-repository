<?php

use Mockery as m;

class IdeaRepositoryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockQuery = m::mock('App\Contracts\QueryInterface');
		$this->mockQueryBuilder = m::mock('Illuminate\Database\Query\Builder');
		$this->mockModel = m::mock('Idea');
		$this->ideaRepository = new App\Repositories\IdeaRepository($this->mockModel);
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('App\Repositories\IdeaRepository', $this->ideaRepository);
	}

}
