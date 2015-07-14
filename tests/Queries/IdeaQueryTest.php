<?php

use Mockery as m;

class IdeaQueryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockIdeaQuery = m::mock('App\Queries\IdeaQuery[addFilter]');
	}

	public function tearDown() {
		m::close();
	}

	public function testItCanBeInstantiated() {
		$this->assertInstanceOf('App\Queries\IdeaQuery', $this->mockIdeaQuery);
		$this->assertInstanceOf('App\Queries\Query', $this->mockIdeaQuery);
		$this->assertInstanceOf('App\Contracts\QueryInterface', $this->mockIdeaQuery);
	}

	public function testItCanFilterBySoapbox() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterBySoapbox', Mockery::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery->filterBySoapbox(1);
	}

	public function testItCanFilterByIds() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterByIds', Mockery::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery->filterByIds([1, 2, 3]);
	}

	public function testItCanFilterByUser() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterByUser', Mockery::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery->filterByUser(1);
	}

	public function testItCanChainFiltersTogether() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterBySoapbox', Mockery::type('callable'))->andReturn(m::self())
			->shouldReceive('addFilter')->once()->with('filterByIds', Mockery::type('callable'))->andReturn(m::self())
			->shouldReceive('addFilter')->once()->with('filterByUser', Mockery::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery
			->filterBySoapbox(1)
			->filterByIds([1, 2, 3])
			->filterByUser(1);	
	}

}
