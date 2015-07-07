<?php

use Mockery as m;

class IdeaRepositoryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockIdea = m::mock('Idea');
		$this->ideaRepository = new App\Repositories\IdeaRepository($this->mockIdea);
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('App\Repositories\IdeaRepository', $this->ideaRepository);
	}

	public function testCanFilterByIds() {
		$ids = [1];

		$this->mockIdea
			->shouldReceive('inSoapbox')->once()->with(1)->andReturn($this->mockIdea)
			->shouldReceive('byIds')->once()->with($ids)->andReturn($this->mockIdea)
			->shouldReceive('get')->once();

		$this->ideaRepository->filterByIds($ids)->get();
	}

	public function testCanFilterByUser() {
		$userId = 2;

		$this->mockIdea
			->shouldReceive('inSoapbox')->once()->with(1)->andReturn($this->mockIdea)
			->shouldReceive('byUser')->once()->with($userId)->andReturn($this->mockIdea)
			->shouldReceive('get')->once();

		$this->ideaRepository->filterByUser($userId)->get();
	}

}
