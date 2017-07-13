<?php

namespace SearchResultsAggregator\DataProviders;

interface DataProviderInterface
{
    public function getUrl(string $searchRequest): string;

    public function getWaitForElementName(): string;

    public function setResponse(string $response);

    public function getData(): array;
}