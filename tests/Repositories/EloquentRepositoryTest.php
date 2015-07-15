<?php

use Mockery as m;
use App\Repositories\EloquentRepository;

class ModelRepository extends EloquentRepository {};

class EloquentRepositoryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockQuery = m::mock('App\Queries\Query');
		$this->mockQueryBuilder = m::mock('Illuminate\Database\Eloquent\Builder');
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

	// -----------------------------------------------------------------
	// 
	// Test GET
	//
	// -----------------------------------------------------------------

	public function testCanGetWithDefaultColumns() {
		$defaultColumns = ['*'];

		$this->mockModel
			->shouldReceive('newQuery')->once()->andReturn($this->mockQueryBuilder);

		$this->mockQuery
			->shouldReceive('getFilters')->once()->andReturn([]);

		$this->mockQueryBuilder
			->shouldReceive('get')->once()->with($defaultColumns);

		$this->modelRepository->get($this->mockQuery);
	}

	public function testCanGetWithSpecificColumns() {
		$columns = ['id', 'idea'];

		$this->mockModel
			->shouldReceive('newQuery')->once()->andReturn($this->mockQueryBuilder);

		$this->mockQuery
			->shouldReceive('getFilters')->once()->andReturn([]);

		$this->mockQueryBuilder
			->shouldReceive('get')->once()->with($columns);

		$this->modelRepository->get($this->mockQuery, $columns);
	}

	public function testFilterClosureReceivesQueryBuilderWhenGetMethodCalled() {
		$columns = ['id', 'idea'];

		$mockReturn = [
			function ($query) {
				$this->assertSame($this->mockQueryBuilder, $query);
				$this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $query);
			}
		];

		$this->mockModel
			->shouldReceive('newQuery')->once()->andReturn($this->mockQueryBuilder);

		$this->mockQuery
			->shouldReceive('getFilters')->once()->andReturn($mockReturn);

		$this->mockQueryBuilder
			->shouldReceive('get')->once()->with($columns);

		$this->modelRepository->get($this->mockQuery, $columns);
	}

	// -----------------------------------------------------------------
	// 
	// Test FIND
	//
	// -----------------------------------------------------------------

	public function testCanFindWithDefaultColumns() {
		$id = 1;
		$defaultColumns = ['*'];

		$this->mockModel
			->shouldReceive('newQuery')->once()->andReturn($this->mockQueryBuilder);

		$this->mockQuery
			->shouldReceive('getFilters')->once()->andReturn([]);

		$this->mockQueryBuilder
			->shouldReceive('find')->once()->with($id, $defaultColumns);

		$this->modelRepository->find($id, $this->mockQuery);
	}

	public function testCanFindWithSpecificColumns() {
		$id = 1;
		$columns = ['id', 'idea'];

		$this->mockModel
			->shouldReceive('newQuery')->once()->andReturn($this->mockQueryBuilder);

		$this->mockQuery
			->shouldReceive('getFilters')->once()->andReturn([]);

		$this->mockQueryBuilder
			->shouldReceive('find')->once()->with($id, $columns);

		$this->modelRepository->find($id, $this->mockQuery, $columns);
	}

	public function testFilterClosureReceivesQueryBuilderWhenFindMethodCalled() {
		$id = 1;
		$columns = ['id', 'idea'];

		$mockReturn = [
			function ($query) {
				$this->assertSame($this->mockQueryBuilder, $query);
				$this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $query);
			}
		];

		$this->mockModel
			->shouldReceive('newQuery')->once()->andReturn($this->mockQueryBuilder);

		$this->mockQuery
			->shouldReceive('getFilters')->once()->andReturn($mockReturn);

		$this->mockQueryBuilder
			->shouldReceive('find')->once()->with($id, $columns);

		$this->modelRepository->find($id, $this->mockQuery, $columns);
	}

	// -----------------------------------------------------------------
	// 
	// Test SAVE
	//
	// -----------------------------------------------------------------

	public function testCanSave() {
		$this->mockModel
			->shouldReceive('save')->once()
			->andReturn(true);

		$result = $this->modelRepository->save($this->mockModel);
		
		$this->assertTrue($result);
	}

	// -----------------------------------------------------------------
	// 
	// Test DELETE
	//
	// -----------------------------------------------------------------

	public function testCanDelete() {
		$this->mockModel
			->shouldReceive('delete')->once()
			->andReturn(true);

		$result = $this->modelRepository->delete($this->mockModel);
		
		$this->assertTrue($result);
	}

	// -----------------------------------------------------------------
	// 
	// Test PURGE
	//
	// -----------------------------------------------------------------

	public function testCanPurge() {
		$this->mockModel
			->shouldReceive('forceDelete')->once()
			->andReturn(true);

		$result = $this->modelRepository->purge($this->mockModel);
		
		$this->assertTrue($result);
	}

}
