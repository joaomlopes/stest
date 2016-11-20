<?php

namespace Uniplaces\STest;

use Uniplaces\STest\Listing\Listing;

class ListingFinder implements ListingFinderInterface {
	/**
	 * @var string
	 */
	protected $searchType;

	/**
	 * @param string $searchType simple|advanced
	 */
	public function __construct( $searchType = 'simple' ) {
		$this->searchType = $searchType;
	}

	/**
	 * @param Listing[] $listings
	 * @param array $search
	 *
	 * @return Listing[]
	 */
	public function reduce( array $listings, array $search ) {
		$matchListings = [];

		foreach ( $listings as $listing ) {

			$results = $this->handleSearch( $listing, $search );

			if ( $results ) {
				$matchListings[] = $listing;
			}
		}

		return $matchListings;
	}

	/**
	 * @param $listing
	 * @param array $search
	 */
	public function handleSearch( $listing, array $search ) {

	}
}
