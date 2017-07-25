<?php

require __DIR__.'/vendor/autoload.php';

use SearchResultsAggregator\WebDrivers\Phantomjs as PhantomjsDriver;
use SearchResultsAggregator\DataProviders\{Google, Yahoo, GroupDataProvider};
use SearchResultsAggregator\Aggregator;

$searchRequestString = $argv[1] ?? '';
if (empty($searchRequestString)) {
    echo "No search request.\n";
    die;
}

$dataProvider = new Google(new PhantomjsDriver('http://phantomjs:8910'));
$results = $dataProvider->search($searchRequestString);
foreach ($results as $result) {
    $result->print();
}

$dataProvider = new Yahoo(new PhantomjsDriver('http://phantomjs:8910'));
$results = $dataProvider->search($searchRequestString);
foreach ($results as $result) {
    $result->print();
}

$groupDataProvider = new GroupDataProvider(
    new Aggregator(),
    new Google(new PhantomjsDriver('http://phantomjs:8910')),
    new Yahoo(new PhantomjsDriver('http://phantomjs:8910'))
);
$results = $groupDataProvider->search($searchRequestString);
foreach ($results as $result) {
    $result->print();
}