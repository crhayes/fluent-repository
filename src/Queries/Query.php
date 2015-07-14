<?php namespace App\Queries;

use Closure;
use App\Contracts\QueryInterface;

class Query implements QueryInterface {

	protected $filters = [];

	public function getFilters() {
		return $this->filters;
	}

	public function addFilter($name, Closure $filter) {
		$this->filters[$name] = $filter;
	}

	public function removeFilter($name) {
		unset($this->filters[$name]);
	}
	
}