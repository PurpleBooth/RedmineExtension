<?php

namespace spec\PurpleBooth\Behat\RedmineExtension\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PurpleBoothBehatRedmineExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PurpleBooth\Behat\RedmineExtension\DependencyInjection\PurpleBoothBehatRedmineExtension');
    }
    function it_is_a_behat_extension()
    {
        $this->shouldHaveType('Behat\Behat\Extension\ExtensionInterface');
    }

    function it_should_load_services(ContainerBuilder $mockContainer)
    {
        $this->load(array(), $mockContainer);
    }

    function it_should_have_a_get_config_method(ArrayNodeDefinition $mockConfig) {
        $this->getConfig($mockConfig);
    }

    function it_should_have_no_compiler_passes() {
        $this->getCompilerPasses()->shouldReturn(array());
    }
}
