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
print_r($dataProvider->search($searchRequestString));

$dataProvider = new Yahoo(new PhantomjsDriver('http://phantomjs:8910'));
print_r($dataProvider->search($searchRequestString));

$aggregator = new Aggregator(new PhantomjsDriver('http://phantomjs:8910'));
$aggregator->addDataProvider('SearchResultsAggregator\DataProviders\Google');
$aggregator->addDataProvider('SearchResultsAggregator\DataProviders\Yahoo');
print_r($aggregator->search($searchRequestString));
