<?php

namespace Uniplaces\STest;

use Uniplaces\STest\Listing\Listing;
use Uniplaces\STest\Requirement\StayTime;
use Uniplaces\STest\Requirement\TenantTypes;
use DateTime;

class ListingFinder implements ListingFinderInterface
{
    /**
     * @var string
     */
    protected $searchType;

    /**
     * @param string $searchType simple|advanced
     */
    public function __construct($searchType = 'simple')
    {
        $this->searchType = $searchType;
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
            if ($listing->getLocalization()->getCity() != $search['city']) {
                continue;
            }

            $stayTime = $listing->getRequirements()->getStayTime();
            if (isset($search['start_date']) && $stayTime instanceof StayTime) {
                /** @var DateTime $startDate */
                $startDate = $search['start_date'];
                /** @var DateTime $endDate */
                $endDate = $search['end_date'];

                $interval = $endDate->diff($startDate);
                $days = (int)$interval->format('%a');

                if ($days < $stayTime->getMin() || $days > $stayTime->getMax()) {
                    continue;
                }
            }


            $tenantTypes = $listing->getRequirements()->getTenantTypes();
            if ($tenantTypes instanceof TenantTypes && !in_array($search['occupation'], $tenantTypes->toArray())) {
                continue;
            }


            if ($this->searchType == 'advanced') {
                if (isset($search['address'])) {
                    $listingAddress = strtolower(trim($listing->getLocalization()->getAddress()));
                    $address = strtolower(trim($search['address']));

                    if (levenshtein($listingAddress, $address) > 5) {
                        continue;
                    }
                }

                if (isset($search['price'])) {
                    $listingPrice = $listing->getPrice();
                    $min = isset($search['price']['min']) ? $search['price']['min'] : null;
                    $max = isset($search['price']['max']) ? $search['price']['max'] : null;

                    if (($min !== null && $min > $listingPrice) || ($max !== null && $max < $listingPrice)) {
                        continue;
                    }
                }
            }

            $matchListings[] = $listing;
        }

        return $matchListings;
    }
}
