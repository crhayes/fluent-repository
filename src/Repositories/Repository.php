<?php namespace App\Repositories;

use Closure;

abstract class Repository {

	protected $filters = [];

	protected function get() {
		foreach ($this->filters as $filter) {
			$filter();
		}
	}

	public function getFilters() {
		return $this->filters;
	}

	protected function addFilter($name, Closure $filter) {
		$this->filters[$name] = $filter;
	}

	protected function removeFilter($name) {
		unset($this->filters[$name]);
	}

}
