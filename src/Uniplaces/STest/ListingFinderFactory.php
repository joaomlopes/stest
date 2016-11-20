<?php

namespace Uniplaces\STest;

use Uniplaces\STest\Listing\Listing;

/**
 * ListingFinderFactory
 */
abstract class ListingFinderFactory {
	/**
	 * @return ListingFinderInterface
	 */
	public static function createSimple() {
		return new HandleListingSearchSimple();
	}

	/**
	 * @return ListingFinderInterface
	 */
	public static function createAdvanced() {
		return new HandleListingSearchAdvanced();
	}
}
