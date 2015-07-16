<?php namespace SoapBox\Ideas\Eloquent;

use SoapBox\FilterBag as BaseFilterBag;
use SoapBox\Ideas\Contracts\FilterBag as IdeaFilterBag;

class FilterBag extends BaseFilterBag implements IdeaFilterBag {

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
