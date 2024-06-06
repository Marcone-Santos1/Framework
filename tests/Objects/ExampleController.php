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
        return Response::json($this->service);
    }

    public function getTestService()
    {
        return Response::json($this->testService);
    }

    public function handleRequest()
    {
        return Response::json($this->service->doSomething());
    }

    public function testWithoutDI()
    {
        return Response::json('testWithoutDI');
    }

    public function handleRequestTest()
    {
        return Response::json($this->service->getRepository()->getExamples());
    }

    public function testMethod(ExampleService $exampleService): Response
    {
        return Response::json($exampleService->sayHello());
    }

    public function testParam(int $id, ExampleService $exampleService, ExampleService $exampleService1): Response
    {
        return Response::json("Received ID: $id");
    }

    public function testParam2(int $id, bool $isReal, ExampleService $exampleService, ExampleService $exampleService1): Response
    {
        return Response::json([$id, $isReal, $exampleService->sayHello(), $exampleService1->sayHello()]);
    }


    public function index(): Response
    {
        return Response::json($this->service->sayHello());
    }
}