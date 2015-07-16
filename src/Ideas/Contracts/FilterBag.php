<?php namespace SoapBox\Ideas\Contracts;

interface FilterBag {

	public function filterBySoapbox($id);

	public function filterByIds(array $ids);

	public function filterByUser($userId);

}
