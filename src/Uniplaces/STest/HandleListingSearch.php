<?php
/**
 * Created by PhpStorm.
 * User: joaomlopes
 * Date: 29/10/2016
 * Time: 19:48
 */

namespace Uniplaces\STest;

use Uniplaces\STest\Listing\Listing;
use Uniplaces\STest\Requirement\StayTime;
use Uniplaces\STest\Requirement\TenantTypes;

class HandleListingSearch {

	/**
	 * @param $listing_city
	 * @param $search
	 *
	 * @return bool
	 */
	public function searchCity( $listing_city, $search ) {
		if ( $listing_city != $search['city'] ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $stayTime
	 * @param $search
	 *
	 * @return bool
	 */
	public function searchStayTime( $stayTime, $search ) {
		if ( isset( $search['start_date'] ) && $stayTime instanceof StayTime ) {
			/** @var DateTime $startDate */
			$startDate = $search['start_date'];
			/** @var DateTime $endDate */
			$endDate = $search['end_date'];

			$interval = $endDate->diff( $startDate );
			$days     = (int) $interval->format( '%a' );

			if ( $days < $stayTime->getMin() || $days > $stayTime->getMax() ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param $tenantTypes
	 * @param $search
	 *
	 * @return bool
	 */
	public function searchTenantType( $tenantTypes, $search ) {
		if ( $tenantTypes instanceof TenantTypes && ! in_array( $search['occupation'], $tenantTypes->toArray() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $listing_address
	 * @param $search
	 *
	 * @return bool
	 */
	public function searchAddress( $listing_address, $search ) {
		if ( isset( $search['address'] ) ) {
			$listingAddress = strtolower( trim( $listing_address ) );
			$address        = strtolower( trim( $search['address'] ) );

			if ( levenshtein( $listingAddress, $address ) > 5 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param $listing_price
	 * @param $search
	 *
	 * @return bool
	 */
	public function searchPrice( $listing_price, $search ) {
		if ( isset( $search['price'] ) ) {
			$minPricing = isset( $search['price']['min'] ) ? $search['price']['min'] : null;
			$maxPricing = isset( $search['price']['max'] ) ? $search['price']['max'] : null;

			if ( ( $minPricing !== null && $minPricing > $listing_price ) || ( $maxPricing !== null && $maxPricing < $listing_price ) ) {
				return false;
			}
		}

		return true;
	}
}