<?php namespace App\Queries;

use App\Contracts\IdeaQueryInterface;

class IdeaQuery extends Query implements IdeaQueryInterface {

	public function filterBySoapbox($id) {
		$this->addFilter('filterBySoapbox', function () use ($id) {
			$this->model->inSoapbox($id);
		});

		return $this;
	}

	public function filterByIds(array $ids) {
		$this->addFilter('filterByIds', function () use ($ids) {
			$this->model->byIds($ids);
		});

		return $this;
	}

	public function filterByUser($userId) {
		$this->addFilter('filterByUser', function () use ($userId) {
			$this->model->byUser($userId);
		});

		return $this;
	}

}
