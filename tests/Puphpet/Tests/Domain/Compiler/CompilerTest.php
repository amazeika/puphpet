<?php

namespace Puphpet\Tests\Domain\PuppetModule;

use Puphpet\Domain\Compiler\Compiler;

use Symfony\Component\EventDispatcher;

class CompilerTest extends \PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $template = '/foo/bar.twig';
        $configuration = ['foo' => 'bar'];
        $rendered = 'hello world';
        $additionalContent = 'foo bar';
        $compilationContent = "hello world\nfoo bar";

        $twig = $this->getMockBuilder(\Twig_Environment::class)
            ->disableOriginalConstructor()
            ->setMethods(['render'])
            ->getMock();

        $twig->expects($this->once())
            ->method('render')
            ->with($template, $configuration)
            ->will($this->returnValue($rendered));

        $dispatcher = $this->buildEventDispatcher();
        $dispatcher->expects($this->atLeastOnce())
            ->method('dispatch')
            ->with('compile.foo.finish')
            ->will(
                $this->returnCallback(
                    function ($eventName, $event) use ($additionalContent) {
                        $event->getCompilation()->addContent($additionalContent);
                    }
                )
            );

        $compiler = new Compiler($dispatcher, $twig, $template, 'foo');
        $result = $compiler->compile($configuration);

        $this->assertEquals($compilationContent, $result);
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
