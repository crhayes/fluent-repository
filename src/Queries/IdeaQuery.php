<?php namespace SoapBox\Queries;

use SoapBox\Contracts\IdeaQueryInterface;

class IdeaQuery extends Query implements IdeaQueryInterface {

	public function filterBySoapbox($id) {
		$this->addFilter('filterBySoapbox', function ($query) use ($id) {
			$query->inSoapbox($id);
		});

		return $this;
	}

	public function filterByIds(array $ids) {
		$this->addFilter('filterByIds', function ($query) use ($ids) {
			$query->byIds($ids);
		});

		return $this;
	}

	public function filterByUser($userId) {
		$this->addFilter('filterByUser', function ($query) use ($userId) {
			$query->byUser($userId);
		});

		return $this;
	}

}
