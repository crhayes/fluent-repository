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
	// Test helpers
	//
	// -----------------------------------------------------------------

	private function createNewQueryBuilderFromModel() {
		$this->mockModel
			->shouldReceive('newQuery')->once()->andReturn($this->mockQueryBuilder);
	}

	private function shouldNotUseFilters() {
		$this->mockQuery
			->shouldNotReceive('getFilters');
	}

	private function shouldUseFiltersAndReturn($return) {
		$this->mockQuery
			->shouldReceive('getFilters')->once()->andReturn($return);
	}

	private function getMockedFilterClosure() {
		return [
			function ($query) {
				$this->assertSame($this->mockQueryBuilder, $query);
				$this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $query);
			}
		];
	}

	// -----------------------------------------------------------------
	// 
	// Test GET
	//
	// -----------------------------------------------------------------

	public function testCanGetWithDefaults() {
		$defaultColumns = ['*'];
		$mockedResult = 'this is a mocked result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('get')->once()->with($defaultColumns)->andReturn($mockedResult);

		$result = $this->modelRepository->get();

		$this->assertSame($result, $mockedResult);
	}

	public function testCanGetWithSpecificColumns() {
		$columns = ['id', 'idea'];
		$mockedResult = 'this is a mocked result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('get')->once()->with($columns)->andReturn($mockedResult);

		$result = $this->modelRepository->get(null, $columns);

		$this->assertSame($result, $mockedResult);
	}

	public function testGetCallsGetFiltersWhenQueryObjectProvided() {
		$defaultColumns = ['*'];
		$mockedResult = 'this is a mocked result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldUseFiltersAndReturn([]);

		$this->mockQueryBuilder
			->shouldReceive('get')->once()->with($defaultColumns)->andReturn($mockedResult);

		$result = $this->modelRepository->get($this->mockQuery);

		$this->assertSame($result, $mockedResult);
	}

	public function testFilterClosureReceivesQueryBuilderWhenGetMethodCalled() {
		$columns = ['id', 'idea'];
		$mockedResult = 'this is a mocked result';

		$mockReturn = $this->getMockedFilterClosure();

		$this->createNewQueryBuilderFromModel();

		$this->shouldUseFiltersAndReturn($mockReturn);

		$this->mockQueryBuilder
			->shouldReceive('get')->once()->with($columns)->andReturn($mockedResult);

		$result = $this->modelRepository->get($this->mockQuery, $columns);

		$this->assertSame($result, $mockedResult);
	}

	// -----------------------------------------------------------------
	// 
	// Test FIND
	//
	// -----------------------------------------------------------------

	public function testCanFindWithDefaults() {
		$id = 1;
		$columns = ['*'];
		$mockedResult = 'this is a mocked result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('find')->once()->with($id, $columns)->andReturn($mockedResult);

		$result = $this->modelRepository->find($id);

		$this->assertSame($result, $mockedResult);
	}

	public function testCanFindWithSpecificColumns() {
		$id = 1;
		$columns = ['id', 'idea'];
		$mockedResult = 'this is a mocked result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('find')->once()->with($id, $columns)->andReturn($mockedResult);

		$result = $this->modelRepository->find($id, null, $columns);

		$this->assertSame($result, $mockedResult);
	}

	public function testFindCallsGetFiltersWhenQueryObjectProvided() {
		$id = 1;
		$columns = ['*'];
		$mockedResult = 'this is a mocked result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldUseFiltersAndReturn([]);

		$this->mockQueryBuilder
			->shouldReceive('find')->once()->with($id, $columns)->andReturn($mockedResult);

		$result = $this->modelRepository->find($id, $this->mockQuery);

		$this->assertSame($result, $mockedResult);
	}

	public function testFilterClosureReceivesQueryBuilderWhenFindMethodCalled() {
		$id = 1;
		$columns = ['id', 'idea'];
		$mockedResult = 'this is a mocked result';

		$mockReturn = $this->getMockedFilterClosure();

		$this->createNewQueryBuilderFromModel();

		$this->shouldUseFiltersAndReturn($mockReturn);

		$this->mockQueryBuilder
			->shouldReceive('find')->once()->with($id, $columns)->andReturn($mockedResult);

		$result = $this->modelRepository->find($id, $this->mockQuery, $columns);

		$this->assertSame($result, $mockedResult);
	}

	// -----------------------------------------------------------------
	// 
	// Test PAGINATE
	//
	// -----------------------------------------------------------------

	public function testCanPaginateWithDefaults() {
		$defaultPerPage = 10;
		$defaultPage = 1;
		$defaultColumns = ['*'];

		$offset = $defaultPerPage * ($defaultPage - 1);
		$count = 10;

		$mockInterimResult = 'interim result';
		$mockPaginatedResult = 'paginated result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('take')->once()->with($defaultPerPage)->andReturn($this->mockQueryBuilder)
			->shouldReceive('offset')->once()->with($offset)->andReturn($this->mockQueryBuilder)
			->shouldReceive('get')->once()->with($defaultColumns)->andReturn($mockInterimResult)
			->shouldReceive('count')->once()->andReturn($count);

		$this->mockPaginator
			->shouldReceive('make')->once()->with($mockInterimResult, $count, $defaultPerPage, $defaultPage)->andReturn($mockPaginatedResult);

		$result = $this->modelRepository->paginate();

		$this->assertSame($result, $mockPaginatedResult);
	}

	public function testCanPaginateWithSpecificParameters() {
		$perPage = 20;
		$page = 3;
		$defaultColumns = ['*'];

		$offset = $perPage * ($page - 1);
		$count = 20;

		$mockInterimResult = 'interim result';
		$mockPaginatedResult = 'paginated result';

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('take')->once()->with($perPage)->andReturn($this->mockQueryBuilder)
			->shouldReceive('offset')->once()->with($offset)->andReturn($this->mockQueryBuilder)
			->shouldReceive('get')->once()->with($defaultColumns)->andReturn($mockInterimResult)
			->shouldReceive('count')->once()->andReturn($count);

		$this->mockPaginator
			->shouldReceive('make')->once()->with($mockInterimResult, $count, $perPage, $page)->andReturn($mockPaginatedResult);

		$result = $this->modelRepository->paginate($perPage, $page);

		$this->assertSame($result, $mockPaginatedResult);
	}

	public function testFilterClosureReceivesQueryBuilderWhenPaginateMethodCalled() {
		$perPage = 20;
		$page = 3;
		$defaultColumns = ['*'];

		$offset = $perPage * ($page - 1);
		$count = 20;

		$mockInterimResult = 'interim result';
		$mockPaginatedResult = 'paginated result';
		$mockReturn = $this->getMockedFilterClosure();

		$this->createNewQueryBuilderFromModel();

		$this->shouldUseFiltersAndReturn($mockReturn);

		$this->mockQueryBuilder
			->shouldReceive('take')->once()->with($perPage)->andReturn($this->mockQueryBuilder)
			->shouldReceive('offset')->once()->with($offset)->andReturn($this->mockQueryBuilder)
			->shouldReceive('get')->once()->with($defaultColumns)->andReturn($mockInterimResult)
			->shouldReceive('count')->once()->andReturn($count);

		$this->mockPaginator
			->shouldReceive('make')->once()->with($mockInterimResult, $count, $perPage, $page)->andReturn($mockPaginatedResult);

		$result = $this->modelRepository->paginate($perPage, $page, $this->mockQuery);

		$this->assertSame($result, $mockPaginatedResult);
	}

	// -----------------------------------------------------------------
	// 
	// Test CHUNK
	//
	// -----------------------------------------------------------------

	public function testCanChunkWithDefaults() {
		$perChunk = 50;
		$callback = function () {
			return 'this is a callback';
		};
		$defaultColumns = ['*'];
		$mockedResult = ['this is a chunked result'];

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('select')->once()->with($defaultColumns)->andReturn($this->mockQueryBuilder)
			->shouldReceive('chunk')->once()->with($perChunk, $callback)->andReturn($mockedResult);

		$result = $this->modelRepository->chunk($perChunk, $callback);

		$this->assertSame($result, $mockedResult);
	}

	public function testCanChunkWithSpecificColumns() {
		$perChunk = 50;
		$callback = function () {
			return 'this is a callback';
		};
		$columns = ['id', 'idea'];
		$mockedResult = ['this is a chunked result'];

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('select')->once()->with($columns)->andReturn($this->mockQueryBuilder)
			->shouldReceive('chunk')->once()->with($perChunk, $callback)->andReturn($mockedResult);

		$result = $this->modelRepository->chunk($perChunk, $callback, null, $columns);

		$this->assertSame($result, $mockedResult);
	}

	public function testFilterClosureReceivesQueryBuilderWhenChunkMethodCalled() {
		$perChunk = 50;
		$callback = function () {
			return 'this is a callback';
		};
		$defaultColumns = ['*'];
		$mockedResult = ['this is a chunked result'];
		$mockReturn = $this->getMockedFilterClosure();

		$this->createNewQueryBuilderFromModel();

		$this->shouldUseFiltersAndReturn($mockReturn);

		$this->mockQueryBuilder
			->shouldReceive('select')->once()->with($defaultColumns)->andReturn($this->mockQueryBuilder)
			->shouldReceive('chunk')->once()->with($perChunk, $callback)->andReturn($mockedResult);

		$result = $this->modelRepository->chunk($perChunk, $callback, $this->mockQuery);

		$this->assertSame($result, $mockedResult);
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
