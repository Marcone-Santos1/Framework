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

    public function handleRequestTest()
    {
        return Response::json($this->service->getRepository()->getExamples());
    }

    public function index(): string {
        return $this->service->sayHello();
    }
}