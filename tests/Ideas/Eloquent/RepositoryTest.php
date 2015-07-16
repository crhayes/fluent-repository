<?php namespace Tests\Ideas\Eloquent;

use Mockery as m;
use SoapBox\Ideas\Eloquent\Repository;

class RepositoryTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockFilterBag = m::mock('SoapBox\FilterBag');
		$this->mockQueryBuilder = m::mock('Illuminate\Database\Query\Builder');
		$this->mockModel = m::mock('SoapBox\Models\Idea');
		$this->mockPaginator = m::mock('SoapBox\Paginator');
		$this->ideaRepository = new Repository($this->mockModel, $this->mockPaginator);
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\Ideas\Eloquent\Repository', $this->ideaRepository);
	}

}
