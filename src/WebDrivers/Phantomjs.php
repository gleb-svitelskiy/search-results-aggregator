<?php

namespace SearchResultsAggregator\WebDrivers;

use Facebook\WebDriver\Remote\{DesiredCapabilities, RemoteWebDriver};
use Facebook\WebDriver\{WebDriver, WebDriverExpectedCondition, WebDriverBy};

class Phantomjs implements WebDriverInterface
{
    /**
     * @const Timeout for the connect phase to remote Phantomjs server (milliseconds)
     */
    const CONNECTION_TIMEOUT = 5000;

    /**
     * @const Request timeout to load web page (seconds)
     */
    const REQUEST_TIMEOUT = 10;

    /**
     * @var WebDriver
     */
    protected $webDriver;

    public function __construct(string $remoteWebDriverUrl)
    {
        if (empty($remoteWebDriverUrl)) {
            throw new \BadMethodCallException('$remoteWebDriverUrl is empty');
        }
        register_shutdown_function([$this, '__destruct']);

        $this->webDriver = RemoteWebDriver::create(
            $remoteWebDriverUrl,
            DesiredCapabilities::phantomjs(),
            self::CONNECTION_TIMEOUT
        );
    }

    public function __destruct()
    {
        if ($this->webDriver instanceof WebDriver) {
            $this->webDriver->quit();
        }
    }

    public function getPageSource(string $url, string $waitForElement): string
    {
        $this->webDriver->get($url);
        $this->webDriver->wait(self::REQUEST_TIMEOUT)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::id($waitForElement)
            )
        );

        return $this->webDriver->getPageSource();
    }
}