<?php namespace SoapBox\Ideas\Eloquent;

use Idea;
use SoapBox\EloquentRepository;
use SoapBox\Ideas\Contracts\Repository as IdeaRepository;

class Repository extends EloquentRepository implements IdeaRepository {

	protected $model;

	public function __construct(Idea $model) {
		$this->model = $model;
	}

}
