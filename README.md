# Simple test

Simple practical test.

Try to improve as much as possible the code in the finder class (the rest is just entities an object value):
https://github.com/uniplaces/stest/blob/master/src/Uniplaces/STest/ListingFinder.php

Don't change the tests, they all must run:
https://github.com/uniplaces/stest/blob/master/tests/Uniplaces/STest/Tests/FindListingsTest.php#L171

After refactoring here's what we expect:
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

#### How I Solve
Since the size of ListingFinder should be minimum as possible and without being required to change it when adding new rules, 
I decided to create an auxiliary class and create a method for each rule known.
Everytime that the method got returned as false, jumps to another index of the foreach cycle without adding the listing to the matchListings.
To get new search types without changing the ListingFinder, I created a method (customSearchType) where, after being coded, will return a 
variable($validateSearch) and continue to the next index if the value is false.
Even it was asked to avoid hard-coded values on ListingFinder, I had to let stay the verification for the search type as advanced and 
added one more to run only if it isn't simple or advanced. Certainly there's some work around to avoid this, but since the purpose of the
test is to get values from a search, it makes sense to leave this hardcoded.