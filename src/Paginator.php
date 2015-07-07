<?php namespace App;

use Illuminate\Pagination\LengthAwarePaginator;

class Paginator {

	public function make($items, $total, $perPage, $currentPage = null, array $options = []) {
		return new LengthAwarePaginator($items, $total, $perPage, $currentPage, $options);
	}

}
