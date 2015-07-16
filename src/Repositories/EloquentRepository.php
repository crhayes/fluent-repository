<?php namespace SoapBox\Repositories;

use Closure;
use SoapBox\Paginator;
use SoapBox\Contracts\QueryInterface;
use SoapBox\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository implements RepositoryInterface {

	protected $model;
	protected $paginator;

	public function __construct(Model $model, Paginator $paginator) {
		$this->model = $model;
		$this->paginator = $paginator;
	}

	public function get(QueryInterface $query = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($query) {
			foreach ($query->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		return $queryBuilder->get($columns);
	}

	public function find($id, QueryInterface $query = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($query) {
			foreach ($query->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		return $queryBuilder->find($id, $columns);
	}

	public function paginate($perPage = 10, $page = 1, QueryInterface $query = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($query) {
			foreach ($query->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		$queryBuilder->take($perPage)->offset($perPage * ($page - 1));

        return $this->paginator->make($queryBuilder->get($columns), $queryBuilder->count(), $perPage, $page);
	}

	public function chunk($perChunk, Closure $callback, QueryInterface $query = null, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		if ($query) {
			foreach ($query->getFilters() as $filter) {
				$filter($queryBuilder);
			}
		}

		return $queryBuilder->select($columns)->chunk($perChunk, $callback);
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

}
