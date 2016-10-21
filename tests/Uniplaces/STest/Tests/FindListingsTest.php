<?php

namespace Uniplaces\STest\Tests;

use PHPUnit_Framework_TestCase;
use Uniplaces\STest\Listing\Listing;
use Uniplaces\STest\Localization\Localization;
use Uniplaces\STest\Requirement\Requirements;
use Uniplaces\STest\Requirement\StayTime;
use Uniplaces\STest\Requirement\TenantTypes;
use Uniplaces\STest\ListingFinderFactory;
use DateTime;

class FindListingsTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleSearchIgnoresPrice()
    {
        $listings = $this->search(
            [
                'city' => 'Lisbon',
                'price' => array('min' => 1000),
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        // the price was ignored so expecting 2
        $this->assertCount(2, $listings);
    }

    /**
     * @param string $city
     * @param int    $nrExpectRes
     *
     * @dataProvider providerCity
     */
    public function testCity($city, $nrExpectRes)
    {
        $listings = $this->search(
            [
                'city' => $city,
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );
        $this->assertCount($nrExpectRes, $listings);
        foreach ($listings as $listing) {
            $this->assertEquals($city, $listing->getLocalization()->getCity());
        }
    }

    /**
     * @return array
     */
    public function providerCity()
    {
        return [
            ['Lisbon', 2],
            ['Oporto', 2],
            ['Faro', 0]
        ];
    }

    public function testAddress()
    {
        $listings = $this->searchAdvanced(
            [
                'city' => 'Lisbon',
                'address' => 'Avenida Liberade',
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        $this->assertCount(1, $listings);
        /** @var Listing $listing */
        $listing = current($listings);
        $this->assertEquals('Avenida da Liberdade', $listing->getLocalization()->getAddress());
    }

    public function testPrice()
    {
        // test min
        $listings = $this->searchAdvanced(
            [
                'city' => 'Lisbon',
                'price' => ['min' => 1000],
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        $this->assertCount(1, $listings);
        /** @var Listing $listing */
        $listing = current($listings);
        $this->assertEquals(3000, $listing->getPrice());

        // test max
        $listings = $this->searchAdvanced(
            [
                'city' => 'Lisbon',
                'price' => ['max' => 1000],
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        $this->assertCount(1, $listings);
        /** @var Listing $listing */
        $listing = current($listings);
        $this->assertEquals(900, $listing->getPrice());

        // test range
        $listings = $this->searchAdvanced(
            [
                'city' => 'Lisbon',
                'price' => ['min' => 200, 'max' => 800],
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        $this->assertCount(0, $listings);

        // test exactly
        $listings = $this->searchAdvanced(
            [
                'city' => 'Lisbon',
                'price' => ['min' => 900, 'max' => 900],
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        $this->assertCount(1, $listings);
        /** @var Listing $listing */
        $listing = current($listings);
        $this->assertEquals(900, $listing->getPrice());
    }

    public function testDates()
    {
        $listings = $this->search(
            [
                'city' => 'Lisbon',
                'start_date' => DateTime::createFromFormat('j-M-Y', '15-May-2014'),
                'end_date' => DateTime::createFromFormat('j-M-Y', '15-Jun-2014'),
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        $this->assertCount(1, $listings);
        /** @var Listing $listing */
        $listing = current($listings);
        $this->assertEquals('Avenida da Liberdade', $listing->getLocalization()->getAddress());

        $listings = $this->search(
            [
                'city' => 'Lisbon',
                'start_date' => DateTime::createFromFormat('j-M-Y', '15-May-2014'),
                'end_date' => DateTime::createFromFormat('j-M-Y', '15-Jul-2014'),
                'occupation' => TenantTypes::UNDERGRADUATE
            ]
        );

        $this->assertCount(2, $listings);
    }

    public function testTenantType()
    {
        $listings = $this->search(
            [
                'city' => 'Lisbon',
                'occupation' => TenantTypes::RESEARCHER
            ]
        );

        $this->assertCount(1, $listings);
        /** @var Listing $listing */
        $listing = current($listings);
        $this->assertNull($listing->getRequirements()->getTenantTypes());
    }

    /**
     * @param array $search
     *
     * @return Listing[]
     */
    protected function search(array $search)
    {
        return ListingFinderFactory::createSimple()->reduce($this->getListings(), $search);
    }

    /**
     * @param array $search
     *
     * @return Listing[]
     */
    protected function searchAdvanced(array $search)
    {
        return ListingFinderFactory::createAdvanced()->reduce($this->getListings(), $search);
    }

    /**
     * @return Listing[]
     */
    protected function getListings()
    {
        return [
            new Listing(
                new Localization('Lisbon', 'Rua Luz Soriano', 67),
                new Requirements(new StayTime(50, 600), null),
                3000
            ),
            new Listing(
                new Localization('Lisbon', 'Avenida da Liberdade', 30),
                new Requirements(null, new TenantTypes(array(TenantTypes::UNDERGRADUATE, TenantTypes::TEACHER))),
                900
            ),
            new Listing(
                new Localization('Oporto', 'Avenida da Boavista', 91),
                new Requirements(null, new TenantTypes(array(TenantTypes::UNDERGRADUATE))),
                700
            ),
            new Listing(
                new Localization('Oporto', 'Rua de Santa Catarina', 91),
                new Requirements(null, new TenantTypes(array(TenantTypes::UNDERGRADUATE))),
                700
            ),
        ];
    }
}
