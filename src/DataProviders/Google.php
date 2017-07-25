<?php

namespace SearchResultsAggregator\DataProviders;

use Symfony\Component\DomCrawler\Crawler;
use SearchResultsAggregator\Results\{ResultCollection, Result};

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

    protected function getResult(string $html): ResultCollection
    {
        // todo move to parser instance
        $collection = new ResultCollection();
        $crawler = new Crawler($html);
        $crawler->filterXPath(
            self::DATA_XPATH
        )->each(
            function ($node) use ($collection) {
                parse_str(parse_url($node->attr('href'), PHP_URL_QUERY), $hrefParams);
                $isValidUrl = parse_url($hrefParams['q'], PHP_URL_HOST);
                if (!$isValidUrl) {
                    return;
                }
                $collection->append(new Result($node->text(), $hrefParams['q'], ['Google']));
            }
        );

        return $collection;

    }
}
