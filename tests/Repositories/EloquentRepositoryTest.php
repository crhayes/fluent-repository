<?php

use Mockery as m;
use App\Repositories\EloquentRepository;

class ModelRepository extends EloquentRepository {};

class EloquentRepositoryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockModel = m::mock('Illuminate\Database\Eloquent\Model');
		$this->mockPaginator = m::mock('App\Paginator');
		$this->modelRepository = new ModelRepository($this->mockModel, $this->mockPaginator);
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('App\Repositories\EloquentRepository', $this->modelRepository);
	}

}
