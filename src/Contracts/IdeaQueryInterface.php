<?php namespace App\Contracts;

interface IdeaQueryInterface extends QueryInterface {

	public function filterBySoapbox($id);

	public function filterByIds(array $ids);

	public function filterByUser($userId);

}
