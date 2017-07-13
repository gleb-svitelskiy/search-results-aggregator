<?php

require __DIR__.'/vendor/autoload.php';

use SearchResultsAggregator\DataScraper;
use SearchResultsAggregator\WebDrivers\Phantomjs as PhantomjsDriver;
use SearchResultsAggregator\DataProviders\{Google, Yahoo};

$searchRequestString = $argv[1] ?? '';
if (empty($searchRequestString)) {
    echo "No search request.\n";
    die;
}

$dataScraper = new DataScraper(
    new PhantomjsDriver('http://phantomjs:8910'),
    [
        new Google(),
        new Yahoo(),
    ]
);
$results = $dataScraper->search($searchRequestString);
print_r($results);