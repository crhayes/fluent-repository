<?php namespace SoapBox;

use Paginator as IlluminatePaginator;

class Paginator {

	public function make($items, $total, $perPage, $currentPage = null, array $options = []) {
		return IlluminatePaginator::make($items->toArray(), $total, $perPage, $currentPage, $options);
	}

}
