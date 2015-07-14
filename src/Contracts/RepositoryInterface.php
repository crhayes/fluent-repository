<?php namespace App\Contracts;

use Model;
use Closure;

interface RepositoryInterface {

	public function get(QueryInterface $query, array $columns);

	public function find(QueryInterface $query, $id, array $columns);

	public function paginate(QueryInterface $query, $perPage, $page, array $columns);

	public function chunk(QueryInterface $query, $perChunk, Closure $callback);

	public function save(Model $model);

	public function delete(Model $model);

	public function purge(Model $model);

}

