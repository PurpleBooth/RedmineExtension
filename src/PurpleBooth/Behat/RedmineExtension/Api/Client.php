<?php

namespace PurpleBooth\Behat\RedmineExtension\Api;

use Redmine\Client as Api;

/**
 * Proxy to the useful bits of the redmine API
 *
 * @package PurpleBooth\Behat\RedmineExtension\Api
 */
class Client
{

    /**
     * @var \Redmine\Client
     */
    private $apiClient;

    /**
     * @param Api $client
     */
    public function __construct(Api $client)
    {
        $this->apiClient = $client;
    }

    /**
     * Get a single redmine issue
     *
     * @param $number
     * @return array
     */
    public function getIssue($number)
    {
        $issue = $this->apiClient->api('issue')->show($number);

        if ($issue === false) {
            return false;
        }

        return $issue['issue'];
    }

    /**
     * Get multiple redmine issues (all of the issues for a project, or just all of the issues
     *
     * @param null|string $project
     * @return array
     */
    public function getIssues($project = null)
    {
        $options = array();

        if ($project !== null) {
            $options['project_id'] = $project;
        }

        $issues = $this->apiClient->api('issue')->all($options);

        if (!array_key_exists('issues', $issues)) {
            return array();
        }

        return $issues['issues'];
    }
}