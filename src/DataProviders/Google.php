<?php

namespace SearchResultsAggregator\DataProviders;

use Symfony\Component\DomCrawler\Crawler;

class Google implements DataProviderInterface
{
    /**
     * @const
     */
    protected const SEARCH_RESULT_ENDPOINT = 'https://www.google.com/?gws_rd=cr#nfpr=1&q=%s';

    /**
     * @const
     */
    protected const DATA_XPATH = '//*[@id="ires"]/ol/div/h3/a';

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
        return 'resultStats';
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
                parse_str(parse_url($node->attr('href'), PHP_URL_QUERY), $hrefParams);
                $isValidUrl = parse_url($hrefParams['q'], PHP_URL_HOST);
                if (!$isValidUrl) {
                    return [];
                }

                return [
                    'Title' => $node->text(),
                    'Url'   => $hrefParams['q'],
                    'Source' => 'Google',
                ];
            }
        );

        return array_values(array_filter($data));

    }
}