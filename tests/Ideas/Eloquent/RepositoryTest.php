<?php namespace Tests\Ideas\Eloquent;

use Mockery as m;
use SoapBox\Ideas\Eloquent\Repository;

class RepositoryTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockFilterBag = m::mock('SoapBox\FilterBag');
		$this->mockQueryBuilder = m::mock('Illuminate\Database\Query\Builder');
		$this->mockModel = m::mock('Idea');
		$this->ideaRepository = new Repository($this->mockModel);
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\Ideas\Eloquent\Repository', $this->ideaRepository);
	}

}
