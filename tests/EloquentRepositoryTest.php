<?php namespace Tests;

use Exception;
use Mockery as m;
use SoapBox\EloquentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModelRepository extends EloquentRepository {};

class EloquentRepositoryTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->mockFilterBag = m::mock('SoapBox\FilterBag');
		$this->mockQueryBuilder = m::mock('Illuminate\Database\Eloquent\Builder');
		$this->mockModel = m::mock('Illuminate\Database\Eloquent\Model');
		$this->mockPaginator = m::mock('SoapBox\Paginator');
		$this->modelRepository = new ModelRepository($this->mockModel, $this->mockPaginator);
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\EloquentRepository', $this->modelRepository);
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
		$this->mockFilterBag
			->shouldNotReceive('getFilters');
	}

	private function shouldUseFiltersAndReturn($return) {
		$this->mockFilterBag
			->shouldReceive('getFilters')->once()->andReturn($return);
	}

	private function getMockedFilterClosure() {
		$this->mockQueryBuilder
			->shouldReceive('testFilter')->once();

		return [
			function ($query) {
				$this->mockQueryBuilder->testFilter();
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

		$result = $this->modelRepository->get($this->mockFilterBag);

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

		$result = $this->modelRepository->get($this->mockFilterBag, $columns);

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
			->shouldReceive('findOrFail')->once()->with($id, $columns)->andReturn($mockedResult);

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
			->shouldReceive('findOrFail')->once()->with($id, $columns)->andReturn($mockedResult);

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
			->shouldReceive('findOrFail')->once()->with($id, $columns)->andReturn($mockedResult);

		$result = $this->modelRepository->find($id, $this->mockFilterBag);

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
			->shouldReceive('findOrFail')->once()->with($id, $columns)->andReturn($mockedResult);

		$result = $this->modelRepository->find($id, $this->mockFilterBag, $columns);

		$this->assertSame($result, $mockedResult);
	}

	/**
	 * @expectedException SoapBox\Exceptions\RecordNotFoundException
	 */
	public function testModelNotFoundExceptionConvertedToRecordNotFoundException() {
		$id = 1;
		$columns = ['*'];

		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();

		$this->mockQueryBuilder
			->shouldReceive('findOrFail')->once()->with($id, $columns)->andThrow(new ModelNotFoundException);

		$this->modelRepository->find($id);
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

		$result = $this->modelRepository->paginate($perPage, $page, $this->mockFilterBag);

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

		$result = $this->modelRepository->chunk($perChunk, $callback, $this->mockFilterBag);

		$this->assertSame($result, $mockedResult);
	}

	// -----------------------------------------------------------------
	// 
	// Test COUNT
	//
	// -----------------------------------------------------------------

	public function testCanCountWithoutFilters() {
		$mockedResult = 10;
		
		$this->createNewQueryBuilderFromModel();

		$this->shouldNotUseFilters();
		
		$this->mockQueryBuilder
			->shouldReceive('count')->once()->andReturn($mockedResult);

		$result = $this->modelRepository->count();

		$this->assertSame($result, $mockedResult);
	}

	public function testCanCountWithFilters() {
		$mockedResult = 10;
		$mockReturn = $this->getMockedFilterClosure();

		$this->createNewQueryBuilderFromModel();

		$this->shouldUseFiltersAndReturn($mockReturn);

		$this->mockQueryBuilder
			->shouldReceive('count')->once()->andReturn($mockedResult);

		$result = $this->modelRepository->count($this->mockFilterBag);

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

	/**
	 * @expectedException SoapBox\Exceptions\DatabaseException
	 */
	public function testExceptionConvertedToDatabaseExceptionWhenSaveMethodCalled() {
		$this->mockModel
			->shouldReceive('save')->once()
			->andThrow(new Exception);

		$this->modelRepository->save($this->mockModel);
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

	/**
	 * @expectedException SoapBox\Exceptions\DatabaseException
	 */
	public function testExceptionConvertedToDatabaseExceptionWhenDeleteMethodCalled() {
		$this->mockModel
			->shouldReceive('delete')->once()
			->andThrow(new Exception);

		$this->modelRepository->delete($this->mockModel);
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

	/**
	 * @expectedException SoapBox\Exceptions\DatabaseException
	 */
	public function testExceptionConvertedToDatabaseExceptionWhenPurgeMethodCalled() {
		$this->mockModel
			->shouldReceive('forceDelete')->once()
			->andThrow(new Exception);

		$this->modelRepository->purge($this->mockModel);
	}

	// -----------------------------------------------------------------
	// 
	// Test TRANSACTION
	//
	// -----------------------------------------------------------------

	public function testCanProvideClosureToBeWrappedInATransaction() {
		$mockConnection = m::mock('Illuminate\Database\Connection');
		$mockedResult = 'this will be transacted.';
		$mockedTransactionClosure = function () use ($mockedResult) {
			return $mockedResult;
		};

		$this->mockModel
			->shouldReceive('getConnection')->once()->andReturn($mockConnection);

		$mockConnection
			->shouldReceive('transaction')->once()->with($mockedTransactionClosure)->andReturnUsing($mockedTransactionClosure);

		$result = $this->modelRepository->transaction($mockedTransactionClosure);

		$this->assertSame($result, $mockedResult);
	}

}
