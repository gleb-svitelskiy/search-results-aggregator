<?php

require __DIR__.'/vendor/autoload.php';

use SearchResultsAggregator\WebDrivers\Phantomjs as PhantomjsDriver;
use SearchResultsAggregator\DataProviders\{Google, Yahoo};
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

$aggregator = new Aggregator(new PhantomjsDriver('http://phantomjs:8910'));
$aggregator->addDataProvider('SearchResultsAggregator\DataProviders\Google');
$aggregator->addDataProvider('SearchResultsAggregator\DataProviders\Yahoo');
$results = $aggregator->search($searchRequestString);
foreach ($results as $result) {
    $result->print();
}