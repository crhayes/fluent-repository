<?php

use Mockery as m;
use SoapBox\Queries\Query;

class QueryTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->query = new Query();
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\Queries\Query', $this->query);
		$this->assertInstanceOf('SoapBox\Contracts\QueryInterface', $this->query);
	}

	public function testItContainsNoFiltersByDefault() {
		$filters = $this->query->getFilters();

		$this->assertSame($filters, []);
	}

	public function testItCanAddAFilter() {
		$filter = function () {
			return 'filter';
		};

		$this->query->addFilter('filter', $filter);

		$this->assertSame(count($this->query->getFilters()), 1);
		$this->assertSame(
			$this->query->getFilters(), 
			['filter' => $filter]
		);
	}

	public function testItCanAddTwoDifferentFilters() {
		$filter1 = function () {
			return 'filter 1';
		};
		$filter2 = function () {
			return 'filter 2';
		};

		$this->query->addFilter('filter1', $filter1);
		$this->query->addFilter('filter2', $filter2);

		$this->assertSame(count($this->query->getFilters()), 2);
		$this->assertSame(
			$this->query->getFilters(), 
			['filter1' => $filter1, 'filter2' => $filter2]
		);
	}

	public function testAddingSameFilterOverwritesPreviousFilter() {
		$filter1 = function () {
			return 'filter 1';
		};
		$filter2 = function () {
			return 'filter 2';
		};

		$this->query->addFilter('filter1', $filter1);
		$this->query->addFilter('filter1', $filter2);

		$this->assertSame(count($this->query->getFilters()), 1);
		$this->assertSame(
			$this->query->getFilters(), 
			['filter1' => $filter2]
		);
	}

	public function testRemoveFilter() {
		$filter1 = function () {
			return 'filter 1';
		};

		$this->query->addFilter('filter1', $filter1);
		$this->assertSame(count($this->query->getFilters()), 1);

		$this->query->removeFilter('filter1');
		$this->assertSame(count($this->query->getFilters()), 0);
	}

}
