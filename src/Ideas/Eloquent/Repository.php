<?php namespace SoapBox\Ideas\Eloquent;

use SoapBox\Paginator;
use SoapBox\Models\Idea;
use SoapBox\EloquentRepository;
use SoapBox\Ideas\Contracts\Repository as IdeaRepository;

class Repository extends EloquentRepository implements IdeaRepository {

	public function __construct(Idea $model, Paginator $paginator) {
		$this->model = $model;
		$this->paginator = $paginator;
	}

}