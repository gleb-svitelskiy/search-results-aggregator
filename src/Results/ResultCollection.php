<?php

namespace SearchResultsAggregator\Results;

class ResultCollection extends \ArrayIterator
{
    public function append($result)
    {
        if (!($result instanceof Result)) {
            throw new \Exception('wrong $result type');
        }
        parent::append($result);
    }
}
