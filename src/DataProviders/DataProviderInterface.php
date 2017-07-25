<?php

namespace SearchResultsAggregator\DataProviders;

interface DataProviderInterface
{
    public function search(string $searchRequest): array;
}
