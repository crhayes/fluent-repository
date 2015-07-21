<?php namespace SoapBox;

use Closure;

class FilterBag {

	/**
	 * @var array
	 */
	private $filters = [];

	/**
	 * Return all of the filters currently stored in this filter bag.
	 * 
	 * @return array
	 */
	public function getFilters() {
		return $this->filters;
	}

	/**
	 * Add a filter to this filter bag.
	 * 
	 * @param string
	 * @param Closure
	 */
	public function addFilter($name, Closure $filter) {
		$this->filters[$name] = $filter;
	}

	/**
	 * Remove a filter from this filter bag.
	 * 
	 * @param  string
	 */
	public function removeFilter($name) {
		unset($this->filters[$name]);
	}
	
}