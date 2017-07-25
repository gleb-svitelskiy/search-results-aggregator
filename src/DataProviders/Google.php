<?php

namespace SearchResultsAggregator\DataProviders;

use Symfony\Component\DomCrawler\Crawler;

class Google extends DataProviderAbstract
{
    /**
     * @const
     */
    protected const SEARCH_RESULT_ENDPOINT = 'https://www.google.com/?gws_rd=cr#nfpr=1&q=%s';

    /**
     * @const
     */
    protected const WAIT_FOR_ELEMENT_NAME = 'resultStats';

    /**
     * @const
     */
    protected const DATA_XPATH = '//*[@id="ires"]/ol/div/h3/a';

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
