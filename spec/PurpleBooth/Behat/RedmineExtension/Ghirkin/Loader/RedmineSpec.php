<?php

namespace spec\PurpleBooth\Behat\RedmineExtension\Ghirkin\Loader;

use Behat\Gherkin\Node\FeatureNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PurpleBooth\Behat\RedmineExtension\Api\Client;
use PurpleBooth\Behat\RedmineExtension\Parser\Exception\RedmineUrlInvalid;
use PurpleBooth\Behat\RedmineExtension\Parser\IssueParser;
use PurpleBooth\Behat\RedmineExtension\Parser\RedmineUrl;

class RedmineSpec extends ObjectBehavior
{
    /**
     * Mock
     *
     * @var Client
     */
    private $redmineClient;

    /**
     * Mock
     *
     * @var RedmineUrl
     */
    private $redmineUrl;

    /**
     * Mock
     *
     * @var IssueParser
     */
    private $issueParser;

    function let(Client $redmineClient, RedmineUrl $redmineUrl, IssueParser $issueParser)
    {
        $this->beConstructedWith($redmineClient, $redmineUrl, $issueParser);

        $this->redmineClient = $redmineClient;
        $this->redmineUrl = $redmineUrl;
        $this->issueParser = $issueParser;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PurpleBooth\Behat\RedmineExtension\Ghirkin\Loader\Redmine');
    }

    function it_is_a_ghirkin_loader()
    {
        $this->shouldHaveType('Behat\Gherkin\Loader\AbstractFileLoader');
    }

    function it_supports_loading_all_features()
    {
        $this->supports('')->shouldReturn(true);
    }

    function it_does_not_support_http()
    {
        $this->redmineUrl->setUrl('http://www.google.com/example.feature')->willThrow(new RedmineUrlInvalid());

        $this->supports('http://www.google.com/example.feature')->shouldReturn(false);
    }

    function it_does_support_specific_redmine_issues()
    {
        $this->redmineClient->getIssue("1142")->willReturn(array('example-issue'));
        $this->redmineUrl->getProject()->willReturn(null);
        $this->redmineUrl->getIssueId()->willReturn("1142");
        $this->redmineUrl->setUrl('redmine:///1142')->willReturn(null);

        $this->supports('redmine:///1142')->shouldReturn(true);
    }

    function it_does_support_specific_redmine_projects()
    {
        $this->redmineClient->getIssues('example-project')->willReturn(array('example-issues'));
        $this->redmineUrl->getProject()->willReturn('example-project');
        $this->redmineUrl->getIssueId()->willReturn(null);
        $this->redmineUrl->setUrl('redmine://example-project')->willReturn(null);

        $this->supports('redmine://example-project')->shouldReturn(true);
    }

    function it_does_support_all_redmine_issues()
    {
        $this->redmineClient->getIssues(null)->willReturn(array('example-issues'));
        $this->redmineUrl->getProject()->willReturn(null);
        $this->redmineUrl->getIssueId()->willReturn(null);
        $this->redmineUrl->setUrl('redmine://')->willReturn(null);

        $this->supports('redmine://')->shouldReturn(true);
    }

    function it_loads_issue_retried_with_specific_issue_url(FeatureNode $mockFeatureNode)
    {
        $this->redmineClient->getIssue("1142")->willReturn(
            array(
                'id' => "33",
                'description' => 'example-issue-1'
            )
        );
        $this->redmineUrl->getProject()->willReturn(null);
        $this->redmineUrl->getIssueId()->willReturn("1142");
        $this->redmineUrl->setUrl('redmine:///1142')->willReturn(null);
        $this->redmineUrl->setIssue(
            array(
                'id' => "33",
                'description' => 'example-issue-1'
            )
        )->willReturn(null);

        $this->issueParser->parseIssue(
            array(
                'id' => "33",
                'description' => 'example-issue-1'
            ), $this->redmineUrl
        )->willReturn($mockFeatureNode);

        $this->load('redmine:///1142')->shouldReturn(array($mockFeatureNode));
    }

    function it_does_load_features_from_specific_redmine_projects(FeatureNode $mockFeatureNode1, FeatureNode $mockFeatureNode2)
    {
        $this->redmineClient->getIssues('example-project')->willReturn(
            array(
                array(
                    'id' => "33",
                    'description' => 'example-issue-1'
                ),
                array(
                    'id' => "34",
                    'description' => 'example-issue-2'
                )
            )
        );
        $this->redmineUrl->getProject()->willReturn('example-project');
        $this->redmineUrl->getIssueId()->willReturn(null);
        $this->redmineUrl->setUrl('redmine://example-project')->willReturn(null);
        $this->redmineUrl->setIssue(array(
            'id' => "33",
            'description' => 'example-issue-1'
        ))->willReturn(null);
        $this->redmineUrl->setIssue(array(
            'id' => "34",
            'description' => 'example-issue-2'
        ))->willReturn(null);

        $this->issueParser->parseIssue(
            array(
                'id' => "33",
                'description' => 'example-issue-1'
            )
            , $this->redmineUrl
        )->willReturn($mockFeatureNode1);
        $this->issueParser->parseIssue(
            array(
                'id' => "34",
                'description' => 'example-issue-2'
            ), $this->redmineUrl
        )->willReturn($mockFeatureNode2);

        $this->load('redmine://example-project')->shouldReturn(array($mockFeatureNode1, $mockFeatureNode2));
    }

    function it_does_load_all_features_redmine_issues(FeatureNode $mockFeatureNode1, FeatureNode $mockFeatureNode2)
    {
        $this->redmineClient->getIssues(null)->willReturn(
            array(
                array(
                    'id' => "33",
                    'description' => 'example-issue-1'
                ),
                array(
                    'id' => "34",
                    'description' => 'example-issue-2'
                )
            )
        );

        $this->redmineUrl->getProject()->willReturn(null);
        $this->redmineUrl->getIssueId()->willReturn(null);
        $this->redmineUrl->setUrl('redmine://')->willReturn(null);
        $this->redmineUrl->setIssue(array(
            'id' => "33",
            'description' => 'example-issue-1'
        ))->willReturn(null);
        $this->redmineUrl->setIssue(array(
            'id' => "34",
            'description' => 'example-issue-2'
        ))->willReturn(null);

        $this->issueParser->parseIssue(array(
            'id' => "33",
            'description' => 'example-issue-1'
        ), $this->redmineUrl)->willReturn($mockFeatureNode1);
        $this->issueParser->parseIssue(array(
            'id' => "34",
            'description' => 'example-issue-2'
        ), $this->redmineUrl)->willReturn($mockFeatureNode2);

        $this->load('redmine://')->shouldReturn(array($mockFeatureNode1, $mockFeatureNode2));
    }

    function it_loads_an_empty_resource_string_like_all_features(FeatureNode $mockFeatureNode1, FeatureNode $mockFeatureNode2)
    {
        $this->redmineClient->getIssues(null)->willReturn(
            array(
                array(
                    'id' => "33",
                    'description' => 'example-issue-1'
                ),
                array(
                    'id' => "34",
                    'description' => 'example-issue-2'
                )
            )
        );

        $this->redmineUrl->setIssue(array(
            'id' => "33",
            'description' => 'example-issue-1'
        ))->willReturn(null);
        $this->redmineUrl->setIssue(array(
            'id' => "34",
            'description' => 'example-issue-2'
        ))->willReturn(null);

        $this->issueParser->parseIssue(
            array(
                'id' => "33",
                'description' => 'example-issue-1'
            ), $this->redmineUrl
        )->willReturn($mockFeatureNode1);
        $this->issueParser->parseIssue(
            array(
                'id' => "34",
                'description' => 'example-issue-2'
            ), $this->redmineUrl
        )->willReturn($mockFeatureNode2);

        $this->load('')->shouldReturn(array($mockFeatureNode1, $mockFeatureNode2));
    }
}
