<?php namespace SoapBox\Repositories;

use Idea;
use SoapBox\Contracts\IdeaRepositoryInterface;

class IdeaRepository extends EloquentRepository implements IdeaRepositoryInterface {

	protected $model;

	public function __construct(Idea $model) {
		$this->model = $model;
	}

}
