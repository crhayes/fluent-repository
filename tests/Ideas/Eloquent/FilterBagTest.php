<?php namespace Tests\Ideas\Eloquent;

use Mockery as m;

class FilterBagTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockIdeaQuery = m::mock('SoapBox\Ideas\Eloquent\FilterBag[addFilter]');
	}

	public function tearDown() {
		m::close();
	}

	public function testItCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\Ideas\Eloquent\FilterBag', $this->mockIdeaQuery);
		$this->assertInstanceOf('SoapBox\FilterBag', $this->mockIdeaQuery);
	}

	public function testItCanFilterBySoapbox() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterBySoapbox', m::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery->filterBySoapbox(1);
	}

	public function testItCanFilterByIds() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterByIds', m::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery->filterByIds([1, 2, 3]);
	}

	public function testItCanFilterByUser() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterByUser', m::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery->filterByUser(1);
	}

	public function testItCanChainFiltersTogether() {
		$this->mockIdeaQuery
			->shouldReceive('addFilter')->once()->with('filterBySoapbox', m::type('callable'))->andReturn(m::self())
			->shouldReceive('addFilter')->once()->with('filterByIds', m::type('callable'))->andReturn(m::self())
			->shouldReceive('addFilter')->once()->with('filterByUser', m::type('callable'))->andReturn(m::self());

		$this->mockIdeaQuery
			->filterBySoapbox(1)
			->filterByIds([1, 2, 3])
			->filterByUser(1);	
	}

}
