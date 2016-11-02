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
		return new ListingFinder( 'simple' );
	}

	/**
	 * @return ListingFinderInterface
	 */
	public static function createAdvanced() {
		return new ListingFinder( 'advanced' );
	}
}
