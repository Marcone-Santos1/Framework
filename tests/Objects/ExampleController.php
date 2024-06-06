<?php

namespace MiniRestFramework\Tests\Objects;

use MiniRestFramework\Http\Response\Response;

class ExampleController
{
    private $service;
    private ExampleService $testService;

    public function __construct(
        ExampleService $service,
        ExampleService $testService,

    ) {
        $this->service = $service;
        $this->testService = $testService;
    }

    public function getService()
    {
        return $this->service;
    }

    public function getTestService()
    {
        return $this->testService;
    }

    public function handleRequest()
    {
        return $this->service->doSomething();
    }

    public function testWithoutDI()
    {
        return 'testWithoutDI';
    }

    public function handleRequestTest()
    {
        return Response::json($this->service->getRepository()->getExamples());
    }

    public function testMethod(ExampleService $exampleService): string {
        return $exampleService->sayHello();
    }

    public function testParam(int $id, ExampleService $exampleService, ExampleService $exampleService1): bool|string
    {
        return "Received ID: $id";
    }

    public function testParam2(int $id, bool $isReal, ExampleService $exampleService, ExampleService $exampleService1): bool|string
    {
        return Response::json([$id, $isReal, $exampleService->sayHello(), $exampleService1->sayHello()]);
    }


    public function index(): string {
        return $this->service->sayHello();
    }
}