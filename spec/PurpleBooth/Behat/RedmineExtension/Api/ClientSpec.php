<?php

namespace spec\PurpleBooth\Behat\RedmineExtension\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{

    private $mockClient;

    function let(\Redmine\Client $mockClient, \Redmine\Api\Issue $issueApi)
    {
        $mockClient->api('issue')->willReturn($issueApi);
        $this->mockClient = $issueApi;

        $this->beConstructedWith($mockClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PurpleBooth\Behat\RedmineExtension\Api\Client');
    }

    function it_is_able_to_retrieve_an_issue()
    {
        $apiResponse = array(
            'issue' => array(
                'id' => 1142,
                'name' => "a redmine test",
                'tracker' => array(
                    'id' => 1,
                    'name' => 'Feature'
                ),
                'status' => array(
                    'id' => 1,
                    'name' => 'New'
                ),
                'priority' => array(
                    'id' => 1,
                    'name' => 'Normal'
                ),
                'author' => array(
                    'id' => 58765,
                    'name' => 'Joe Smith'
                ),
                'subject' => "test bug",
                "description" => "",
                "done_ratio" => 0,
                "spent_hours" => 0,
                "created_on" => "2014-05-11T18:47:44Z",
                "updated_on" => "2014-05-11T18:47:44Z"
            ),
        );

        $this->mockClient->show("1142")->willReturn($apiResponse);
        $this->getIssue("1142")->shouldReturn(array(
            'id' => 1142,
            'name' => "a redmine test",
            'tracker' => array(
                'id' => 1,
                'name' => 'Feature'
            ),
            'status' => array(
                'id' => 1,
                'name' => 'New'
            ),
            'priority' => array(
                'id' => 1,
                'name' => 'Normal'
            ),
            'author' => array(
                'id' => 58765,
                'name' => 'Joe Smith'
            ),
            'subject' => "test bug",
            "description" => "",
            "done_ratio" => 0,
            "spent_hours" => 0,
            "created_on" => "2014-05-11T18:47:44Z",
            "updated_on" => "2014-05-11T18:47:44Z"
        ));
    }

    function it_is_able_to_retrieve_all_issues_for_a_project()
    {
        $apiResponse = array('issues' => 'data-here');

        $this->mockClient->all(array('project_id' => "example-project"))->willReturn($apiResponse);
        $this->getIssues("example-project")->shouldReturn('data-here');
    }

    function it_is_able_to_retrieve_all_issues()
    {
        $apiResponse = array('issues' => 'data-here');

        $this->mockClient->all(array())->willReturn($apiResponse);
        $this->getIssues()->shouldReturn('data-here');
    }

    function it_is_able_to_handle_none_existing_issue()
    {
        $expectedResponse = false;

        $this->mockClient->show("1142")->willReturn($expectedResponse);
        $this->getIssue("1142")->shouldReturn($expectedResponse);
    }

    function it_is_able_to_handle_none_existing_issues_for_project()
    {
        $this->mockClient->all(array('project_id' => "example-project"))->willReturn(array());
        $this->getIssues("example-project")->shouldReturn(array());
    }

    function it_is_able_to_handle_no_issues()
    {
        $this->mockClient->all(array())->willReturn(array());
        $this->getIssues()->shouldReturn(array());
    }
}