<?php

namespace Uniplaces\STest;

use Uniplaces\STest\Listing\Listing;

/**
 * Created by PhpStorm.
 * User: joaomlopes
 * Date: 13/11/2016
 * Time: 23:58
 */
class HandleListingSearchSimple extends ListingFinder {

	protected $handleSearch;

	public function __construct() {
		$this->handleSearch = new HandleListingSearch();
	}

	/**
	 * @param Listing $listing
	 * @param array $search
	 *
	 * @return bool
	 */
	public function handleSearch( $listing, array $search ) {

		$exists = $this->handleSearch->searchCity( $listing->getLocalization()->getCity(), $search );

		$exists = $exists && $this->handleSearch->searchStayTime( $listing->getRequirements()->getStayTime(), $search );

		$exists = $exists && $this->handleSearch->searchTenantType( $listing->getRequirements()->getTenantTypes(), $search );

		return $exists;
	}
}