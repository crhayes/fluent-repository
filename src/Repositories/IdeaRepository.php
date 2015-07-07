<?php namespace App\Repositories;

use Idea;
use App\Contracts\IdeaRepositoryInterface;

class IdeaRepository extends EloquentRepository implements IdeaRepositoryInterface {

	protected $model;

	public function __construct(Idea $model) {
		$this->model = $model;

		$this->filterBySoapbox(1);
	}

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
