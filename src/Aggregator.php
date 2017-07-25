<?php

namespace SearchResultsAggregator;

use SearchResultsAggregator\WebDrivers\WebDriverInterface;
use SearchResultsAggregator\DataProviders\DataProviderInterface;

class Aggregator
{
    /**
     * @var WebDriverInterface
     */
    protected $webDriver;

    /**
     * @var DataProviderInterface[]
     */
    protected $dataProviders = [];

    protected function aggregateResults(array $results): array
    {
        $aggregatedResults = [];
        foreach ($results as $result) {
            $key = "{$result['Url']}";
            $isDuplicateResult = isset($aggregatedResults[$key]);
            if ($isDuplicateResult) {
                $aggregatedResults[$key]['Source'][] = $result['Source'];
            } else {
                $aggregatedResults[$key] = [
                    'Title' => $result['Title'],
                    'Url' => $result['Url'],
                    'Source' => [$result['Source']],
                ];
            }
        }

        return array_values($aggregatedResults);
    }

    public function __construct(WebDriverInterface $webDriver)
    {
        $this->webDriver = $webDriver;
    }

    public function addDataProvider(string $dataProvider)
    {
        $dataProvider = new $dataProvider($this->webDriver);
        if (!($dataProvider instanceof DataProviderInterface)) {
            throw new \BadMethodCallException('incorrect $dataProvider');
        }
        $this->dataProviders[] = $dataProvider;
    }

    public function search(string $searchRequest): array
    {
        $results = [];
        foreach ($this->dataProviders as $dataProvider) {
            $results = array_merge($results, $dataProvider->search($searchRequest));
        }

        return $this->aggregateResults($results);
    }
}
