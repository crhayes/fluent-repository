<?php namespace SoapBox;

use DB;
use Closure;
use SoapBox\Paginator;
use SoapBox\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;
use SoapBox\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class EloquentRepository implements Repository {

	protected $model;
	protected $paginator;

	public function __construct(Model $model, Paginator $paginator) {
		$this->model = $model;
		$this->paginator = $paginator;
	}

	public function get(FilterBag $filterBag = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($filterBag) {
			foreach ($filterBag->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		return $queryBuilder->get($columns);
	}

	public function find($id, FilterBag $filterBag = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($filterBag) {
			foreach ($filterBag->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		try {
			return $queryBuilder->findOrFail($id, $columns);
		} catch (ModelNotFoundException $e) {
			throw new RecordNotFoundException();
		}
	}

	public function paginate($perPage = 10, $page = 1, FilterBag $filterBag = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($filterBag) {
			foreach ($filterBag->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		$queryBuilder->take($perPage)->offset($perPage * ($page - 1));

        return $this->paginator->make($queryBuilder->get($columns), $queryBuilder->count(), $perPage, $page);
	}

	public function chunk($perChunk, Closure $callback, FilterBag $filterBag = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($filterBag) {
			foreach ($filterBag->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		return $queryBuilder->select($columns)->chunk($perChunk, $callback);
	}

	public function count(FilterBag $filterBag = null) {
		$queryBuilder = $this->model->newQuery();

		if ($filterBag) {
			foreach ($filterBag->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		return $queryBuilder->count();
	}

	public function save(Model $model) {
		return $model->save();
	}

	public function delete(Model $model) {
		return $model->delete();
	}

	public function purge(Model $model) {
		return $model->forceDelete();
	}

	public function transaction(Closure $callback) {
		return $this->model->getConnection()->transaction($callback);
	}

}
