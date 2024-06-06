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
        $requestClient = new RequestClient('http://localhost:8080');

        // Definindo os headers e opções para a requisição
        $headers = ['Accept' => 'application/json'];
        $options = [
            'headers' => $headers
        ];

        // Enviando a requisição GET para o endpoint /test
        $response = $requestClient->sendRequest('GET', '/test', $options);

        // Verificando os resultados
        $this->assertEquals(200, $response['status_code'], "Expected status code 200");
        $this->assertStringContainsString('Service is working!', $response['body'], "Expected response body to contain 'Service is working!'");
    }
}
