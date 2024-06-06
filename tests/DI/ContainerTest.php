<?php

namespace MiniRestFramework\Tests\DI;

use MiniRestFramework\DI\Container;
use PHPUnit\Framework\TestCase;
use MiniRestFramework\Tests\Objects\ExampleController;
use MiniRestFramework\Tests\Objects\ExampleRepository;
use MiniRestFramework\Tests\Objects\ExampleService;

class ContainerTest extends TestCase
{

    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testAutoResolving() {

        // Exemplo de classe que deve ser resolvida automaticamente
        $exampleController = $this->container->make(ExampleController::class);
        $exampleService = $this->container->make(ExampleService::class);

        $this->assertInstanceOf(ExampleController::class, $exampleController);
        $this->assertInstanceOf(ExampleService::class, $exampleService);
        $this->assertInstanceOf(ExampleService::class, $exampleController->getService());
        $this->assertInstanceOf(ExampleService::class, $exampleController->getTestService());
        $this->assertInstanceOf(ExampleService::class, $exampleController->getTestService());
        $this->assertInstanceOf(ExampleRepository::class, $exampleService->getRepository());
    }

    public function testExampleServiceIsResolved()
    {
        $service = $this->container->make(ExampleService::class);
        $this->assertInstanceOf(ExampleService::class, $service);
        $this->assertEquals("Hello from ExampleService!", $service->sayHello());
    }

    public function testExampleControllerIsResolved()
    {
        $controller = $this->container->make(ExampleController::class);
        $this->assertInstanceOf(ExampleController::class, $controller);
        $this->assertInstanceOf(ExampleService::class, $controller->getService());
    }

    public function testControllerIndexMethod()
    {
        $controller = $this->container->make(ExampleController::class);
        $response = $controller->index();
        $this->assertEquals("Hello from ExampleService!", $response);
    }

    public function testCallMethod()
    {
        $controller = $this->container->make(ExampleController::class);
        $response = $this->container->callMethod($controller, 'testMethod');
        $this->assertEquals("Hello from ExampleService!", $response);
    }


    public function testSingletonBehavior()
    {
        $service1 = $this->container->make(ExampleService::class);
        $service2 = $this->container->make(ExampleService::class);
        $this->assertSame($service1, $service2);
    }

}
