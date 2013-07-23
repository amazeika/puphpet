<?php

namespace Puphpet\Tests\Domain\Compiler\Event\Listener;

use Puphpet\Domain\Compiler\Event\Listener\CompilationListener;
use Puphpet\Domain\Compiler;

class CompilationListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnCompile()
    {
        $compilation = $this->getMockBuilder(Compiler\Compilation::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $event = $this->getMockBuilder(Compiler\Event\CompilationEvent::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCompilation'])
            ->getMock();

        $event->expects($this->once())
            ->method('getCompilation')
            ->will($this->returnValue($compilation));

        $manipulator1 = $this->getMockBuilder(Compiler\ManipulatorInterface::class)
            ->setMethods(['manipulate', 'supports'])
            ->getMock();

        $manipulator1->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true));

        $manipulator1->expects($this->once())
            ->method('manipulate')
            ->with($compilation);

        $manipulator2 = $this->getMockBuilder(Compiler\ManipulatorInterface::class)
            ->setMethods(['manipulate', 'supports'])
            ->getMock();

        $manipulator2->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(false));

        $manipulator2->expects($this->never())
            ->method('manipulate');

        $listener = new CompilationListener([$manipulator1, $manipulator2]);
        $listener->onCompile($event);
    }
}
