<?php
/**
 * Created by IntelliJ IDEA.
 * User: billie
 * Date: 11/05/14
 * Time: 16:23
 */

namespace spec\PurpleBooth\Behat\RedmineExtension\Parser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RedmineUrlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PurpleBooth\Behat\RedmineExtension\Parser\RedmineUrl');
    }

    function it_is_able_to_tell_you_if_an_url_is_a_redmine_url()
    {
        $exception = 'PurpleBooth\Behat\RedmineExtension\Parser\Exception\RedmineUrlInvalid';
        $this->shouldThrow($exception)->duringSetUrl("http://example.org");
    }

    function it_is_not_setting_a_project_for_all_ticket_urls()
    {
        $this->setUrl("redmine:");

        $this->getUrl()->shouldReturn("redmine:");
        $this->getProject()->shouldReturn(null);
    }

    function it_has_a_redmine_url()
    {
        $this->setUrl("redmine:");

        $this->getUrl()->shouldReturn("redmine:");
    }

    function it_is_not_setting_a_issue_for_all_ticket_urls()
    {
        $this->setUrl("redmine:");

        $this->getIssueId()->shouldReturn(null);
    }

    function it_is_able_to_parse_project_out_of_project_only_url()
    {
        $this->setUrl("redmine://example-project");

        $this->getProject()->shouldReturn("example-project");
    }

    function it_is_not_able_to_parse_issue_out_of_project_only_url()
    {
        $this->setUrl("redmine://example-project");

        $this->getIssueId()->shouldReturn(null);
    }

    function it_is_able_to_parse_issue_from_url_without_project()
    {
        $this->setUrl("redmine:///1132");
        $this->getIssueId()->shouldReturn("1132");
    }

    function it_is_able_to_parse_issue_from_alternative_syntax_url()
    {
        $this->setUrl("redmine:/1132");

        $this->getIssueId()->shouldReturn("1132");
    }


    function it_is_not_parsing_path_from_alternative_syntax_url()
    {
        $this->setUrl("redmine:/1132");

        $this->getProject()->shouldReturn(null);
    }


    function it_is_able_to_parse_ticket_number_from_project_url()
    {
        $this->setUrl("redmine://example-project/1132");

        $this->getIssueId()->shouldReturn("1132");
    }

    function it_is_able_to_parse_project_from_project_url()
    {
        $this->setUrl("redmine://example-project/1132");

        $this->getProject()->shouldReturn("example-project");
    }

    function it_is_able_to_build_a_working_url_from_an_issue() {
        $this->setIssue(array('id' => "99"));

        $this->getUrl()->shouldReturn("redmine:///99");
    }
}