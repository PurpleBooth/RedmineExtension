<?php

namespace PurpleBooth\Behat\RedmineExtension\Ghirkin\Loader;

use Behat\Gherkin\Loader\AbstractFileLoader;
use PurpleBooth\Behat\RedmineExtension\Api\Client;
use PurpleBooth\Behat\RedmineExtension\Parser\Exception\RedmineUrlInvalid;
use PurpleBooth\Behat\RedmineExtension\Parser\IssueParser;
use PurpleBooth\Behat\RedmineExtension\Parser\RedmineUrl;

class Redmine extends AbstractFileLoader
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var RedmineUrl
     */
    private $urlParser;

    /**
     * @var IssueParser
     */
    private $issueParser;

    /**
     * @param Client $client
     * @param RedmineUrl $urlParser
     * @param IssueParser $issueParser
     */
    public function __construct(Client $client, RedmineUrl $urlParser, IssueParser $issueParser)
    {
        $this->client = $client;
        $this->urlParser = $urlParser;
        $this->issueParser = $issueParser;
    }

    /**
     * Checks if current loader supports provided resource.
     *
     * @param mixed $resource Resource to load
     *
     * @return Boolean
     */
    public function supports($resource)
    {
        if ($resource == '') {
            return true;
        }

        try {
            $this->urlParser->setUrl($resource);
        } catch (RedmineUrlInvalid $exception) {
            return false;
        }

        if ($this->urlParser->getIssueId()) {
            return (boolean)$this->client->getIssue($this->urlParser->getIssueId());
        } else {
            return 0 < count($this->client->getIssues($this->urlParser->getProject()));
        }

        return false;
    }

    /**
     * Loads features from provided resource.
     *
     * @param mixed $resource Resource to load
     *
     * @return array
     */
    public function load($resource)
    {
        $features = array();
        $issues = array();

        if ($resource == '') {
            $issues = $this->client->getIssues();
        } else {
            $this->urlParser->setUrl($resource);

            if ($this->urlParser->getIssueId()) {
                $issues[] = $this->client->getIssue($this->urlParser->getIssueId());
            } else {
                $issues = $this->client->getIssues($this->urlParser->getProject());
            }
        }

        foreach ($issues as $issue) {
            $this->urlParser->setIssue($issue);
            $features[] = $this->issueParser->parseIssue($issue, $this->urlParser);
        }

        return $features;
    }
}
