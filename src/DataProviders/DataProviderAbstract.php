<?php

namespace SearchResultsAggregator\DataProviders;

use SearchResultsAggregator\WebDrivers\WebDriverInterface;
use SearchResultsAggregator\Results\ResultCollection;

abstract class DataProviderAbstract
{
    abstract protected function getEndpointUrl(): string;
    abstract protected function getWaitForElementName(): string;
    abstract protected function getResult(string $html): ResultCollection;

    /**
     * @var WebDriverInterface
     */
    protected $webDriver;

    protected function getUrl(string $searchRequest): string
    {
        return sprintf($this->getEndpointUrl(), urlencode($searchRequest));
    }

    public function __construct(WebDriverInterface $webDriver)
    {
        $this->webDriver = $webDriver;
    }

    public function search(string $searchRequest): ResultCollection
    {
        $searchRequest = trim($searchRequest);
        if (empty($searchRequest)) {
            throw new \BadMethodCallException('$searchRequest is empty');
        }

        $html = $this->webDriver->getPageSource(
            $this->getUrl($searchRequest),
            $this->getWaitForElementName()
        );

        return $this->getResult($html);
    }
}
