<?php

namespace SearchResultsAggregator\Results;

class Result
{
    protected $title;

    protected $url;

    protected $source;

    public function __construct(string $title, string $url, array $source)
    {
        $this->title = $title;
        $this->url = $url;
        $this->source = $source;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function print()
    {
        print_r([
            'Title' => $this->title,
            'Url' => $this->url,
            'Source' => $this->source,
        ]);
    }
}
