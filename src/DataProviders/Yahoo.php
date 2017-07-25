<?php

namespace SearchResultsAggregator\DataProviders;

use Symfony\Component\DomCrawler\Crawler;
use SearchResultsAggregator\Results\{ResultCollection, Result};

class Yahoo extends DataProviderAbstract
{
    /**
     * @const
     */
    protected const SEARCH_RESULT_ENDPOINT = 'https://search.yahoo.com/search?p=%s';

    /**
     * @const
     */
    protected const WAIT_FOR_ELEMENT_NAME = 'main';

    /**
     * @const
     */
    protected const DATA_XPATH = '//*[@id="web"]/ol/li/div/div/h3/a';

    protected function getEndpointUrl(): string
    {
        return self::SEARCH_RESULT_ENDPOINT;
    }

    protected function getWaitForElementName(): string
    {
        return self::WAIT_FOR_ELEMENT_NAME;
    }

    protected function getResult(string $html): ResultCollection
    {
        // todo move to parser instance
        $collection = new ResultCollection();
        $crawler = new Crawler($html);
        $data = $crawler->filterXPath(
            self::DATA_XPATH
        )->each(
            function ($node) use ($collection) {
                $collection->append(new Result($node->text(), $node->attr('href'), ['Yahoo']));
            }
        );

        return $collection;
    }
}
