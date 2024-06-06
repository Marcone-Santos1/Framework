<?php

namespace MiniRestFramework\Tests\Router;

use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\RequestClient;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testRequestSimulation()
    {
        // Simulando os dados da requisição
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $request = new Request();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/test', $request->getUri());
    }

    public function testDispatchGetRequest()
    {
        // Configurando o ambiente de teste
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'localhost';

        // Inicializando o RequestClient
        $requestClient = new RequestClient('http://localhost:8000');

        // Definindo os headers e opções para a requisição
        $headers = ['Accept' => 'application/json'];
        $options = [
            'headers' => $headers
        ];

        // Enviando a requisição GET para o endpoint /test
        $response = $requestClient->sendRequest('POST', '/test', $options);

        // Verificando os resultados
        $this->assertEquals(200, $response['status_code'], "Expected status code 200");
        $this->assertStringContainsString('Service is working!', $response['body'], "Expected response body to contain 'Service is working!'");
    }

    public function testDispatchGetRequestSayHello()
    {
        // Configurando o ambiente de teste
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'localhost';

        // Inicializando o RequestClient
        $requestClient = new RequestClient('http://localhost:8000');

        // Definindo os headers e opções para a requisição
        $headers = ['Accept' => 'application/json'];
        $options = [
            'headers' => $headers
        ];

        // Enviando a requisição GET para o endpoint /test
        $response = $requestClient->sendRequest('POST', '/sayHello', $options);

        // Verificando os resultados
        $this->assertEquals(200, $response['status_code'], "Expected status code 200");
        $this->assertStringContainsString('Hello from ExampleService!', $response['body'], "Expected response body to contain 'Service is working!'");
    }

    public function testDispatchGetRequestWithRouteParameter() {
        // Configurando o ambiente de teste
        $_SERVER['REQUEST_URI'] = '/testParam/1';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_HOST'] = 'localhost';


        $requestClient = new RequestClient('http://localhost:8000');

        // Definindo os headers e opções para a requisição
        $headers = ['Accept' => 'application/json'];
        $options = [
            'headers' => $headers
        ];

        // Enviando a requisição GET para o endpoint /test
        $response = $requestClient->sendRequest('POST', '/testParam/1', $options);

        // Verificando os resultados
        $this->assertStringContainsString('Received ID: 1', $response['body'], "Expected response body to contain 'Received ID: 1'");
    }

    public function testMiddlewareReceivesRouteParameter() {
        // Configurando o ambiente de teste
        $_SERVER['REQUEST_URI'] = '/testParam/1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'localhost';

        $requestClient = new RequestClient('http://localhost:8000');

        // Definindo os headers e opções para a requisição
        $headers = ['Accept' => 'application/json'];
        $options = [
            'headers' => $headers
        ];


        $num = 2;

        // Enviando a requisição GET para o endpoint /test
        $response = $requestClient->sendRequest('POST', "/testParam2/$num/true", $options);

        // Verificando os resultados
        $this->assertEquals("Received ID: $num", $response['body'], "Expected 'id' parameter to be $num");
    }

    public function testMiddlewareReceivesRouteParameterClosures() {
        // Configurando o ambiente de teste
        $_SERVER['REQUEST_URI'] = '/testClosures/2/true';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_HOST'] = 'localhost';

        $requestClient = new RequestClient('http://localhost:8000');

        // Definindo os headers e opções para a requisição
        $headers = ['Accept' => 'application/json'];
        $options = [
            'headers' => $headers
        ];


        $num = 2;

        // Enviando a requisição GET para o endpoint /test
        $response = $requestClient->sendRequest('POST', "/testClosures/$num/true", $options);

        // Verificando os resultados
        $this->assertEquals("Received ID: $num", $response['body'], "Expected 'id' parameter to be $num");
    }
}
