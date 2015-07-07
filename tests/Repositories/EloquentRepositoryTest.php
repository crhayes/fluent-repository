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

	public function testCanGetWithDefaults() {
		$defaultColumns = ['*'];
		$mockReturn = ['data'];

		$this->mockModel
			->shouldReceive('get')->once()->with($defaultColumns)->andReturn($mockReturn);

		$result = $this->modelRepository->get();

		$this->assertSame($result, $mockReturn);
	}

	public function testCanGetWithSpecificColumns() {
		$columns = ['idea', 'title'];
		$mockReturn = ['data'];

		$this->mockModel
			->shouldReceive('get')->once()->with($columns)->andReturn($mockReturn);

		$result = $this->modelRepository->get($columns);

		$this->assertSame($result, $mockReturn);
	}

	public function testCanFindWithDefaults() {
		$id = 1;
		$defaultColumns = ['*'];
		$mockReturn = ['data'];

		$this->mockModel
			->shouldReceive('find')->once()->with($id, $defaultColumns)->andReturn($mockReturn);

		$result = $this->modelRepository->find($id);

		$this->assertSame($result, $mockReturn);
	}

	public function testCanFindWithSpecificColumns() {
		$id = 1;
		$mockColumns = ['idea', 'title'];
		$mockReturn = ['data'];

		$this->mockModel
			->shouldReceive('find')->once()->with($id, $mockColumns)->andReturn($mockReturn);

		$result = $this->modelRepository->find($id, $mockColumns);

		$this->assertSame($result, $mockReturn);
	}

	public function testCanPaginateWithDefaults() {
		$perPage = 10;
		$page = 1;
		$offset = ($page - 1) * $perPage;
		$count = 1;
		$defaultColumns = ['*'];
		$interimReturn = 'result';
		$mockReturn = ['data'];

		$this->mockModel
			->shouldReceive('take')->once()->with($perPage)->andReturn($this->mockModel)
			->shouldReceive('offset')->once()->with($offset)->andReturn($this->mockModel)
			->shouldReceive('get')->once()->with($defaultColumns)->andReturn($interimReturn)
			->shouldReceive('count')->once()->andReturn($count);

		$this->mockPaginator
			->shouldReceive('make')->once()->with($interimReturn, $count, $perPage, $page)->andReturn($mockReturn);

		$result = $this->modelRepository->paginate();

		$this->assertSame($result, $mockReturn);
	}

	public function testCanPaginateWithSpecificColumns() {
		$perPage = 50;
		$page = 2;
		$offset = ($page - 1) * $perPage;
		$count = 1;
		$mockColumns = ['idea', 'title'];
		$interimReturn = 'result';
		$mockReturn = ['data'];

		$this->mockModel
			->shouldReceive('take')->once()->with($perPage)->andReturn($this->mockModel)
			->shouldReceive('offset')->once()->with($offset)->andReturn($this->mockModel)
			->shouldReceive('get')->once()->with($mockColumns)->andReturn($interimReturn)
			->shouldReceive('count')->once()->andReturn($count);

		$this->mockPaginator
			->shouldReceive('make')->once()->with($interimReturn, $count, $perPage, $page)->andReturn($mockReturn);

		$result = $this->modelRepository->paginate($perPage, $page, $mockColumns);

		$this->assertSame($result, $mockReturn);
	}

	public function testCanChunk() {
		$perChunk = 50;
		$callback = function () {};
		$mockReturn = ['data'];

		$this->mockModel
			->shouldReceive('chunk')->with($perChunk, $callback)->andReturn($mockReturn);

		$result = $this->modelRepository->chunk($perChunk, $callback);

		$this->assertSame($result, $mockReturn);
	}

	public function testCanSave() {
		$this->mockModel
			->shouldReceive('save')->once()
			->andReturn(true);

		$result = $this->modelRepository->save($this->mockModel);

		$this->assertTrue($result);
	}

	public function testCanDelete() {
		$this->mockModel
			->shouldReceive('delete')->once()
			->andReturn(true);

		$result = $this->modelRepository->delete($this->mockModel);

		$this->assertTrue($result);
	}

	public function testCanPurge() {
		$this->mockModel
			->shouldReceive('forceDelete')->once()
			->andReturn(true);

		$result = $this->modelRepository->purge($this->mockModel);

		$this->assertTrue($result);
	}

}
