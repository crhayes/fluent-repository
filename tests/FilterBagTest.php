<?php namespace Tests;

use Mockery as m;
use SoapBox\FilterBag;

class FilterBagTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->filterBag = new FilterBag();
	}

	public function tearDown() {
		m::close();
	}

	public function testCanBeInstantiated() {
		$this->assertInstanceOf('SoapBox\FilterBag', $this->filterBag);
	}

	public function testItContainsNoFiltersByDefault() {
		$filters = $this->filterBag->getFilters();

		$this->assertSame($filters, []);
	}

	public function testItCanAddAFilter() {
		$filter = function () {
			return 'filter';
		};

		$this->filterBag->addFilter('filter', $filter);

		$this->assertSame(count($this->filterBag->getFilters()), 1);
		$this->assertSame(
			$this->filterBag->getFilters(), 
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

		$this->filterBag->addFilter('filter1', $filter1);
		$this->filterBag->addFilter('filter2', $filter2);

		$this->assertSame(count($this->filterBag->getFilters()), 2);
		$this->assertSame(
			$this->filterBag->getFilters(), 
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

		$this->filterBag->addFilter('filter1', $filter1);
		$this->filterBag->addFilter('filter1', $filter2);

		$this->assertSame(count($this->filterBag->getFilters()), 1);
		$this->assertSame(
			$this->filterBag->getFilters(), 
			['filter1' => $filter2]
		);
	}

	public function testRemoveFilter() {
		$filter1 = function () {
			return 'filter 1';
		};

		$this->filterBag->addFilter('filter1', $filter1);
		$this->assertSame(count($this->filterBag->getFilters()), 1);

		$this->filterBag->removeFilter('filter1');
		$this->assertSame(count($this->filterBag->getFilters()), 0);
	}

	public function testRemovingNonExistantFilterDoesNotBlowUp() {
		$this->filterBag->removeFilter('noFilter');
		$this->assertTrue(true);
	}

}
