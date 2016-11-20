<?php

namespace Uniplaces\STest;

use Uniplaces\STest\Listing\Listing;

/**
 * Created by PhpStorm.
 * User: joaomlopes
 * Date: 13/11/2016
 * Time: 23:58
 */
class HandleListingSearchAdvanced extends ListingFinder {

	/**
	 * @var HandleListingSearch
	 */
	protected $handleSearch;

	/**
	 * HandleListingSearchAdvanced constructor.
	 */
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

		$exists = $exists && $this->handleSearch->searchAddress( $listing->getLocalization()->getAddress(), $search );

		if ( ! $exists ) {
			return false;
		}

		return $this->handleSearch->searchPrice( $listing->getPrice(), $search );
	}
}