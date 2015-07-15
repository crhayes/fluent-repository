<?php namespace App\Contracts;

use Model;
use Closure;

interface RepositoryInterface {

	public function get(QueryInterface $query, array $columns);

	public function find($id, QueryInterface $query, array $columns);

	public function paginate($perPage, $page, QueryInterface $query, array $columns);

	public function chunk($perChunk, Closure $callback, QueryInterface $query, array $columns);

	public function save(Model $model);

	public function delete(Model $model);

	public function purge(Model $model);

}

