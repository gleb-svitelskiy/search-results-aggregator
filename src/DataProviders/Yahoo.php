<?php

namespace SearchResultsAggregator\DataProviders;

use Symfony\Component\DomCrawler\Crawler;

class Yahoo implements DataProviderInterface
{
    /**
     * @const
     */
    protected const SEARCH_RESULT_ENDPOINT = 'https://search.yahoo.com/search?p=%s';

    /**
     * @const
     */
    protected const DATA_XPATH = '//*[@id="web"]/ol/li/div/div/h3/a';

    /**
     * @var string search result endpoint response
     */
    protected $response;

    public function getUrl(string $searchRequest): string
    {
        return sprintf(self::SEARCH_RESULT_ENDPOINT, urlencode($searchRequest));
    }

    public function getWaitForElementName(): string
    {
        return 'main';
    }

    public function setResponse(string $response): void
    {
        $this->response = $response;
    }

    public function getData(): array
    {
        $crawler = new Crawler($this->response);
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