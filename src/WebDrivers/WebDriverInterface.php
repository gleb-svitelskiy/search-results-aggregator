<?php

namespace SearchResultsAggregator\WebDrivers;

interface WebDriverInterface
{
    public function getPageSource(string $url, string $waitForElement): string;
}