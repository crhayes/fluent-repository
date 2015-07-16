<?php

use Mockery as m;

class IdeaRepositoryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockFilterBag = m::mock('SoapBox\FilterBag');
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
