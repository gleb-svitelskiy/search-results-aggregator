<?php

namespace SearchResultsAggregator\DataProviders;

use SearchResultsAggregator\Results\ResultCollection;

interface DataProviderInterface
{
    public function search(string $searchRequest): ResultCollection;
}
