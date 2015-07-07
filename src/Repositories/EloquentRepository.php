<?php namespace App\Repositories;

use Closure;
use App\Paginator;
use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository extends Repository implements RepositoryInterface {

	protected $model;
	protected $paginator;

	public function __construct(Model $model, Paginator $paginator) {
		$this->model = $model;
		$this->paginator = $paginator;
	}

	public function get(array $columns = ['*']) {
		parent::get();
		return $this->model->get($columns);
	}

	public function find($id, array $columns = ['*']) {
		return $this->model->find($id, $columns);
	}

	public function paginate($perPage = 10, $page = 1, array $columns = ['*']) {
		$query = $this->model->take($perPage)->offset($perPage * ($page - 1));

        return $this->paginator->make($query->get($columns), $query->count(), $perPage, $page);
	}

	public function chunk($perChunk, Closure $callback) {
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
