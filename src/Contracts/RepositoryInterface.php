<?php namespace App\Contracts;

use Model;
use Closure;

interface RepositoryInterface {

	public function get(array $columns);

	public function find($id, array $columns);

	public function paginate($perPage, $page, array $columns);

	public function chunk($perChunk, Closure $callback);

	public function save(Model $model);

	public function delete(Model $model);

	public function purge(Model $model);

}

