<?php

namespace SearchResultsAggregator;

use SearchResultsAggregator\WebDrivers\WebDriverInterface;
use SearchResultsAggregator\DataProviders\DataProviderInterface;
use SearchResultsAggregator\Results\{ResultCollection, Result};

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

    protected function aggregateResults(array $results): ResultCollection
    {
        $aggregatedResults = [];
        foreach ($results as $result) {
            $key = $result->getUrl();
            $isDuplicateResult = isset($aggregatedResults[$key]);
            if ($isDuplicateResult) {
                array_push($aggregatedResults[$key]['source'], current($result->getSource()));
            } else {
                $aggregatedResults[$key] = [
                    'title' => $result->getTitle(),
                    'url' => $result->getUrl(),
                    'source' => $result->getSource(),
                ];
            }
        }

        $collection = new ResultCollection();
        foreach($aggregatedResults as $result) {
            $collection->append(new Result($result['title'], $result['url'], $result['source']));
        }

        return $collection;
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

    public function search(string $searchRequest): ResultCollection
    {
        $results = [];
        foreach ($this->dataProviders as $dataProvider) {
            $results[] = $dataProvider->search($searchRequest)->getArrayCopy();
        }
        $results = call_user_func_array('array_merge', $results);

        return $this->aggregateResults($results);
    }
}
