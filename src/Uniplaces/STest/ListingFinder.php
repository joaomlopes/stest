<?php

namespace Uniplaces\STest;

use Uniplaces\STest\Listing\Listing;
use Uniplaces\STest\HandleListingSearch;

class ListingFinder implements ListingFinderInterface
{
    /**
     * @var string
     */
    protected $searchType;

	/**
	 * @var \Uniplaces\STest\HandleListingSearch
	 */
	protected $handleSearch;

    /**
     * @param string $searchType simple|advanced
     */
    public function __construct($searchType = 'simple')
    {
        $this->searchType = $searchType;
	    $this->handleSearch = new HandleListingSearch();
    }

    /**
     * @param Listing[] $listings
     * @param array     $search
     *
     * @return Listing[]
     */
    public function reduce(array $listings, array $search)
    {
        $matchListings = [];

        foreach ($listings as $listing) {

	        if ($this->handleSearch->searchCity($listing->getLocalization()->getCity(),$search) == false) {
                continue;
            }

            if($this->handleSearch->searchStayTime($listing->getRequirements()->getStayTime(),$search) == false) {
	            continue;
            }

            if ($this->handleSearch->searchTenantType($listing->getRequirements()->getTenantTypes(), $search) == false) {
                continue;
            }

            if ($this->searchType == 'advanced') {
	            if ($this->handleSearch->searchAddress($listing->getLocalization()->getAddress(), $search) == false) {
		            continue;
	            }

                if ($this->handleSearch->searchPrice($listing->getPrice(), $search) == false) {
	                continue;
                }
            }

	        /**
	         * If search type isn't simple nor advanced
	         * Then runs the method and if it's false continue to the next foreach index
	         */
            if($this->searchType != 'simple' && $this->searchType != 'advanced') {
				if($this->handleSearch->customSearchType($listing, $search) == false) {
					continue;
				}
            }

            $matchListings[] = $listing;
        }

        return $matchListings;
    }
}
