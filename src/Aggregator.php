<?php

namespace SearchResultsAggregator;

use SearchResultsAggregator\Results\{ResultCollection, Result};

class Aggregator
{
    public function aggregateResults(Result ...$results): ResultCollection
    {
        $aggregatedResults = [];
        foreach ($results as $result) {
            $key = $result->getUrl();
            $isDuplicateResult = isset($aggregatedResults[$key]);
            if ($isDuplicateResult) {
                array_push($aggregatedResults[$key]['source'], current($result->getSource()));
            } else {
                $aggregatedResults[$key] = [
                    'title' => $result->getTitle(),
                    'url' => $result->getUrl(),
                    'source' => $result->getSource(),
                ];
            }
        }

        $collection = new ResultCollection();
        foreach($aggregatedResults as $result) {
            $collection->append(new Result($result['title'], $result['url'], $result['source']));
        }

        return $collection;
    }
}
