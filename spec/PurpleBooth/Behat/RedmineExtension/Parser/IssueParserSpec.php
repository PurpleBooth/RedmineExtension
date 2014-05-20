<?php

namespace spec\PurpleBooth\Behat\RedmineExtension\Parser;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PurpleBooth\Behat\RedmineExtension\Parser\RedmineUrl;

class IssueParserSpec extends ObjectBehavior
{
    /**
     * Mock
     *
     * @var Parser
     */
    private $gherkinParser = null;

    function let(Parser $gherkinParser)
    {
        $this->beConstructedWith($gherkinParser);
        $this->gherkinParser = $gherkinParser;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PurpleBooth\Behat\RedmineExtension\Parser\IssueParser');
    }

    function it_should_pull_the_feature_from_the_description_and_the_url_from_the_id(FeatureNode $featureNode, RedmineUrl $url)
    {
        $this->gherkinParser->parse("Given\nWhen\nThen", "redmine://99")->willReturn($featureNode);

        $issueDescription = <<<ISSUE
“There was only one catch and that was Catch-22, which specified that a concern for one's safety in the face of dangers that were real and immediate was the process of a rational mind. Orr was crazy and could be grounded. All he had to do was ask; and as soon as he did, he would no longer be crazy and would have to fly more missions. Orr would be crazy to fly more missions and sane if he didn't, but if he was sane he had to fly them. If he flew them he was crazy and didn't have to; but if he didn't want to he was sane and had to. Yossarian was moved very deeply by the absolute simplicity of this clause of Catch-22 and let out a respectful whistle.

"That's some catch, that Catch-22," he observed.

"It's the best there is," Doc Daneeka agreed.”

<pre class="feature">
Given
When
Then
</pre>

ISSUE;

        $url->getUrl()->willReturn("redmine://99");
        $this->parseIssue(array('description' => $issueDescription), $url)->shouldReturn($featureNode);
    }

    function it_should_treat_a_missing_feature_as_an_empty_file(FeatureNode $featureNode, RedmineUrl $url)
    {
        $this->gherkinParser->parse("", "redmine://99")->willReturn($featureNode);

        $issueDescription = <<<ISSUE
“There was only one catch and that was Catch-22, which specified that a concern for one's safety in the face of dangers that were real and immediate was the process of a rational mind. Orr was crazy and could be grounded. All he had to do was ask; and as soon as he did, he would no longer be crazy and would have to fly more missions. Orr would be crazy to fly more missions and sane if he didn't, but if he was sane he had to fly them. If he flew them he was crazy and didn't have to; but if he didn't want to he was sane and had to. Yossarian was moved very deeply by the absolute simplicity of this clause of Catch-22 and let out a respectful whistle.

"That's some catch, that Catch-22," he observed.

"It's the best there is," Doc Daneeka agreed.”

ISSUE;

        $url->getUrl()->willReturn("redmine://99");
        $this->parseIssue(array('description' => $issueDescription), $url)->shouldReturn($featureNode);
    }
}
