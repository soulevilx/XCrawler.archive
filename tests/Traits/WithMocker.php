<?php

namespace Tests\Traits;

use App\Core\XCrawlerClient;
use GuzzleHttp\Client;
use Jooservices\XcrawlerClient\Factory;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;

trait WithMocker
{
    protected MockInterface $xcrawlerMocker;

    public array $mocks = [];

    protected function getFixture(?string $path): ?string
    {
        if (!$path || !file_exists($this->fixtures.'/'.$path)) {
            return null;
        }

        return file_get_contents($this->fixtures.'/'.$path);
    }

    public function getXCrawlerClientMocker()
    {
        $mocker = \Mockery::mock(XCrawlerClient::class);
        $mocker->shouldReceive('setService')->andReturnSelf();
        $mocker->shouldReceive('init')->andReturnSelf();
        $mocker->shouldReceive('setHeaders')->andReturnSelf();
        $mocker->shouldReceive('setContentType')->andReturnSelf();

        return $mocker;
    }

    public function getXCrawlerClient($response)
    {
        $clientMocker = \Mockery::mock(Client::class);

        if ($response instanceof DomResponse) {
            $clientMocker
                ->shouldReceive('request')
                ->andReturn($response);
        } else {
            $clientMocker
                ->shouldReceive('request')
                ->andThrow($response);
        }

        $mocker = \Mockery::mock(Factory::class);
        $mocker->shouldReceive('enableRetries')->andReturnSelf();
        $mocker->shouldReceive('addOptions')->andReturnSelf();
        $mocker->shouldReceive('enableLogging')->andReturnSelf();
        $mocker->shouldReceive('enableCache')->andReturnSelf();
        $mocker->shouldReceive('make')->andReturn($clientMocker);

        app()->instance(Factory::class, $mocker);
        return new XCrawlerClient('test', new DomResponse());
    }

    /**
     * @return MockInterface
     * @deprecated
     */
    protected function getClientMock(): MockInterface
    {
        return $this->getXCrawlerClientMocker();
    }

    /**
     * Get Successful Mocked External Service Response.
     */
    protected function getSuccessfulMockedResponse(ResponseInterface $response, string $path = null): ResponseInterface
    {
        $response->reset(
            200,
            [],
            $this->getFixture($path) ?? '',
        );

        return $response;
    }

    /**
     * Get Error Mocked External Service Response.
     */
    protected function getErrorMockedResponse(
        ResponseInterface $response,
        ?string $path = null,
        ?int $responseCode = null
    ): ResponseInterface {
        $response->reset(
            $responseCode ?? 500,
            [],
            $this->getFixture($path) ?? '',
        );
        $response->isSucceed = false;

        return $response;
    }

    protected function mockingXCrawler(array $withs)
    {
        foreach ($withs as $data) {
            $expection = $this->xcrawlerMocker->shouldReceive('get');
            $expection = call_user_func_array([$expection, 'with'], $data['args']);
            $succeed = $data['succeed'] ?? true;

            call_user_func_array(
                [$expection, 'andReturn'],
                [
                    $succeed
                        ? $this->getSuccessfulMockedResponse($data['response'] ?? app(DomResponse::class),  $data['andReturn'] ?? null)
                        : $this->getErrorMockedResponse($data['response'] ?? app(DomResponse::class),  $data['andReturn'] ?? null),

                ]
            );
        }
    }
}
