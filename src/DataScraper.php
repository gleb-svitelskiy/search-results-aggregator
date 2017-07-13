<?php

namespace SearchResultsAggregator;

use SearchResultsAggregator\WebDrivers\WebDriverInterface;
use SearchResultsAggregator\DataProviders\DataProviderInterface;

class DataScraper
{
    /**
     * @var WebDriverInterface
     */
    protected $webDriver;

    /**
     * @var DataProviderInterface[]
     */
    protected $dataProviders = [];

    public function __construct(WebDriverInterface $webDriver, array $dataProviders)
    {
        $this->webDriver = $webDriver;
        if (empty($dataProviders)) {
            throw new \BadMethodCallException('$dataProviders is empty');
        }
        // @todo need better solution for interface implementation check
        foreach ($dataProviders as $dataProvider) {
            if (!($dataProvider instanceof DataProviderInterface)) {
                throw new \BadMethodCallException('incorrect $dataProviders');
            }
        }
        unset($dataProvider);
        $this->dataProviders = $dataProviders;
    }

    protected function aggregateResults(): array
    {
        $results = [];
        foreach ($this->dataProviders as $dataProvider) {
            foreach ($dataProvider->getData() as $result) {
                $key = "{$result['Url']}";
                $isDuplicateResult = isset($results[$key]);
                if ($isDuplicateResult) {
                    $results[$key]['Source'][] = $result['Source'];
                } else {
                    $results[$key] = [
                        'Title' => $result['Title'],
                        'Url' => $result['Url'],
                        'Source' => [$result['Source']],
                    ];
                }
            }
        }
        unset($dataProvider, $result);

        return array_values($results);
    }

    public function search(string $searchRequest): array
    {
        $searchRequest = trim($searchRequest);
        if (empty($searchRequest)) {
            throw new \BadMethodCallException('$searchRequest is empty');
        }

        foreach ($this->dataProviders as $dataProvider) {
            $html = $this->webDriver->getPageSource(
                $dataProvider->getUrl($searchRequest),
                $dataProvider->getWaitForElementName()
            );
            $dataProvider->setResponse($html);
        }
        unset($dataProvider);

        return $this->aggregateResults();
    }
}