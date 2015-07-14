<?php namespace App\Contracts;

use Closure;

interface QueryInterface {

	public function getFilters();

	public function addFilter($name, Closure $filter);

	public function removeFilter($name);

}
