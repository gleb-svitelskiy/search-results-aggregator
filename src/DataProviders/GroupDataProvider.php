<?php

namespace SearchResultsAggregator\DataProviders;

use SearchResultsAggregator\Aggregator;
use SearchResultsAggregator\Results\ResultCollection;

class GroupDataProvider implements DataProviderInterface
{
    /**
     * @var Aggregator
     */
    protected $aggregator;

    /**
     * @var DataProviderInterface[]
     */
    protected $dataProviders = [];

    public function __construct(Aggregator $aggregator, DataProviderInterface ...$dataProviders)
    {
        $this->aggregator = $aggregator;
        $this->dataProviders = $dataProviders;
    }

    public function search(string $searchRequest): ResultCollection
    {
        $results = [];
        foreach ($this->dataProviders as $dataProvider) {
            $results[] = $dataProvider->search($searchRequest)->getArrayCopy();
        }
        $results = call_user_func_array('array_merge', $results);

        return empty($results) ? new ResultCollection() : $this->aggregator->aggregateResults(...$results);
    }
}
