<?php namespace SoapBox\Contracts;

use Model;
use Closure;
use SoapBox\FilterBag;

interface Repository {

	public function get(FilterBag $filterBag, array $columns);

	public function find($id, FilterBag $filterBag, array $columns);

	public function paginate($perPage, $page, FilterBag $filterBag, array $columns);

	public function chunk($perChunk, Closure $callback, FilterBag $filterBag, array $columns);

	public function count(FilterBag $filterBag);

	public function save(Model $model);

	public function delete(Model $model);

	public function purge(Model $model);

	public function transaction(Closure $callback);

}

