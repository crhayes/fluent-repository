<?php namespace App\Repositories;

use Closure;
use App\Paginator;
use App\Contracts\QueryInterface;
use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository implements RepositoryInterface {

	protected $model;
	protected $paginator;

	public function __construct(Model $model, Paginator $paginator) {
		$this->model = $model;
		$this->paginator = $paginator;
	}

	public function get(QueryInterface $query, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		foreach ($query->getFilters() as $filter) {
			$filter($queryBuilder);
		}

		return $queryBuilder->get($columns);
	}

	public function find($id, QueryInterface $query, array $columns = ['*']) {
		$queryBuilder = $this->model->newQuery();

		foreach ($query->getFilters() as $filter) {
			$filter($queryBuilder);
		}

		return $queryBuilder->find($id, $columns);
	}

	public function paginate(QueryInterface $query, $perPage = 10, $page = 1, array $columns = ['*']) {
		$query = $this->model->take($perPage)->offset($perPage * ($page - 1));

        return $this->paginator->make($query->get($columns), $query->count(), $perPage, $page);
	}

	public function chunk(QueryInterface $query, $perChunk, Closure $callback) {
		return $this->model->chunk($perChunk, $callback);
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
