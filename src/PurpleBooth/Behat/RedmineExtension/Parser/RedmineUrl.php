<?php

namespace PurpleBooth\Behat\RedmineExtension\Parser;

/**
 * Parse information from the Redmine URL
 *
 * @package PurpleBooth\Behat\RedmineExtension\Api
 */
class RedmineUrl extends \Exception
{

    /**
     * What to put in-front of urls.
     *
     * "redmine://"
     */
    const PROTOCOL_NAME = "redmine";

    /**
     * @var string
     */
    private $url;

    /**
     * Set the URL to be parsed
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $urlPrefix = static::PROTOCOL_NAME . ":";

        if (strncmp($url, $urlPrefix, strlen($urlPrefix)) != 0) {
            throw new Exception\RedmineUrlInvalid("This is not a redmine URL");
        }

        $this->url = $url;
    }

    /**
     * Get the URL as it was set
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Extract the project from the URL.
     *
     * redmine://this-is-the-project
     *
     * Returns null for urls without a project
     *
     * @return string|null
     */
    public function getProject()
    {
        $host = parse_url($this->url, PHP_URL_HOST);

        if (!$host) {
            return null;
        }

        return $host;
    }

    /**
     * Extract the issue from the URL.
     *
     * redmine://this-is-the-project/1142
     *
     * would return 1142
     *
     * Returns null for urls without a project
     *
     * @return string|null
     */
    public function getIssueId()
    {
        $urlPrefix = static::PROTOCOL_NAME . ":///";

        // This is for urls like redmine:///1234 (issue urls without a project)
        if (strncmp($urlPrefix, $this->url, strlen($urlPrefix)) === 0) {
            return substr($this->url, strlen($urlPrefix));
        }

        $path = parse_url($this->url, PHP_URL_PATH);

        if (!$path) {
            return null;
        }

        return substr($path, 1);
    }

    public function setIssue($issue)
    {
        $this->url = static::PROTOCOL_NAME . ":///" . $issue['id'];
    }
}
