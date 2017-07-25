<?php

namespace SearchResultsAggregator\DataProviders;

use Symfony\Component\DomCrawler\Crawler;

class Yahoo extends DataProviderAbstract implements DataProviderInterface
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

    protected function getData(string $html): array
    {
        $crawler = new Crawler($html);
        // @todo need return object, not array
        $data = $crawler->filterXPath(
            self::DATA_XPATH
        )->each(
            function ($node) {
                return [
                    'Title' => $node->text(),
                    'Url'   => $node->attr('href'),
                    'Source' => 'Yahoo',
                ];
            }
        );

        return $data;
    }
}
