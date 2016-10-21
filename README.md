# Simple test

Simple practical test.

Try to improve as much as possible the code in the finder class (the rest is just entities an object value):
https://github.com/uniplaces/stest/blob/master/src/Uniplaces/STest/ListingFinder.php

Don't change the tests, they all must run:
https://github.com/uniplaces/stest/blob/master/tests/Uniplaces/STest/Tests/FindListingsTest.php#L171

After refactoring what here's what we expect:
* ListingFinder and ListingFinderFactory should be easily readable and understandable;
* The size of ListingFinder should be minimum (it's just a class to "query");
* Adding new "rules" should be easy and shouldn't require to touch ListingFinder code directly;
* Hard coded values on ListingFinder shouldn't be hard coded and should be easy to configure;
* Should be easy to add new types of search eg: 'extra-advanced' without touching the ListingFinder code

## Setup

* Fork
* git clone ...
* cd stest
* curl -sS https://getcomposer.org/installer | php
* php composer.phar install --dev
* Run tests: ./vendor/bin/phpunit
