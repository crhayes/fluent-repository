<?php namespace SoapBox\Queries;

use Closure;
use SoapBox\Contracts\QueryInterface;

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