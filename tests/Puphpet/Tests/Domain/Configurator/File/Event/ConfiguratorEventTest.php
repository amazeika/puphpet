<?php

namespace Puphpet\Tests\Domain\Configuration\File;

use Puphpet\Domain\Configurator\File\Event\ConfiguratorEvent;
use Puphpet\Domain;

class ConfiguratorEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $domainFile = $this->getMockBuilder(Domain\File::class)
            ->disableOriginalConstructor()
            ->setMethods(array('addModuleSource'))
            ->getMock();

        $configuration = ['foo' => 'bar'];

        $event = new ConfiguratorEvent($domainFile, $configuration);
        $this->assertEquals($domainFile, $event->getDomainFile());
        $this->assertEquals($configuration, $event->getConfiguration());
    }
}
