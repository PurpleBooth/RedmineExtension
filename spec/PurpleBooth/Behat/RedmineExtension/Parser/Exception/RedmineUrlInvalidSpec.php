<?php

namespace spec\PurpleBooth\Behat\RedmineExtension\Parser\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RedmineUrlInvalidSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PurpleBooth\Behat\RedmineExtension\Parser\Exception\RedmineUrlInvalid');
    }

    function it_is_an_exception()
    {
        $this->shouldHaveType('Exception');
    }
}
