<?php namespace SoapBox;

use Closure;

class FilterBag {

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