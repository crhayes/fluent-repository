<?php namespace App\Repositories;

use Idea;
use App\Contracts\IdeaRepositoryInterface;

class IdeaRepository extends EloquentRepository implements IdeaRepositoryInterface {

	protected $model;

	public function __construct(Idea $model) {
		$this->model = $model;
	}

}
