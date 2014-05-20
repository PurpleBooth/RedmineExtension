<?php

namespace PurpleBooth\Behat\RedmineExtension\Parser;

use Behat\Gherkin\Parser;

class IssueParser
{
    /**
     * @var Parser
     */
    private $ghekinParser;

    public function __construct(Parser $gherkinParser)
    {
        $this->ghekinParser = $gherkinParser;
    }

    public function parseIssue($issue, RedmineUrl $url)
    {
        $feature = $this->extractFeatureFromString($issue['description']);

        return $this->ghekinParser->parse($feature, $url->getUrl());
    }

    /**
     * Extracts a Feature between pre tag from a given string
     *
     * @param string $description
     * @return string
     */
    private function extractFeatureFromString($description)
    {
        $feature = preg_replace('/.*<pre class="feature">(.+?)<\/pre>.*/s', '$1', $description);

        if ($description == $feature) {
            return '';
        }

        return trim($feature);
    }
}
