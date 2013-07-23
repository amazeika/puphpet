<?php

namespace Puphpet\Tests\Domain\Configuration\File;

use Puphpet\Domain\Configurator\File\ConfiguratorHandler;
use Puphpet\Domain;
use Symfony\Component\EventDispatcher;

class ConfiguratorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigureFiresEvent()
    {
        $configuration = [
            'foo'   => 'bar',
            'hello' => 'world'
        ];

        $domainFile = $this->getMockBuilder(Domain\File::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $eventDispatcher = $this->buildEventDispatcher();
        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with('file.configuration');

        $configurationHandler = new ConfiguratorHandler($eventDispatcher);
        $configurationHandler->configure($domainFile, $configuration);
    }

    private function buildEventDispatcher()
    {
        return $this->getMockBuilder(EventDispatcher\EventDispatcherInterface::class)
            ->setMethods(
                [
                    'dispatch',
                    'addListener',
                    'addSubscriber',
                    'removeListener',
                    'removeSubscriber',
                    'getListeners',
                    'hasListeners'
                ]
            )
            ->getMock();
    }
}
