<?php namespace SoapBox\Contracts;

interface IdeaQueryInterface {

	public function filterBySoapbox($id);

	public function filterByIds(array $ids);

	public function filterByUser($userId);

}
