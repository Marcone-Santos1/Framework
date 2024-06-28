<?php

namespace MiniRestFramework\Tests\Objects;

use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;

class ExampleAction
{
    private $service;
    private ExampleService $testService;

    public function __construct(
        ExampleService $service,
        ExampleService $testService,
        Request $request

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

    public function testWithoutDI()
    {
        return 'testWithoutDI';
    }

    public function handleRequestTest()
    {
        return $this->service->getRepository()->getExamples();
    }

    public function testMethod(ExampleService $exampleService)
    {
        return $exampleService->sayHello();
    }

    public function testParam(int $id, ExampleService $exampleService, ExampleService $exampleService1)
    {
        return "Received ID: $id";
    }

    public function testParam2(int $id, bool $isReal, ExampleService $exampleService, ExampleService $exampleService1)
    {
        return [$id, $isReal, $exampleService->sayHello(), $exampleService1->sayHello()];
    }


    public function index()
    {
        return $this->service->sayHello();
    }
}