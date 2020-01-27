<?php

namespace Yosmy\Payment\Gateway\Bluesnap;

use Yosmy\Http;
use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     private: true
 * })
 */
class ExecuteRequest
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var Http\ExecuteRequest
     */
    private $executeRequest;

    /**
     * @var Request\LogEvent
     */
    private $logEvent;

    /**
     * @di\arguments({
     *     host:     "%bluesnap_host%",
     *     username: "%bluesnap_username%",
     *     password: "%bluesnap_password%",
     * })
     *
     * @param string              $host
     * @param string              $username
     * @param string              $password
     * @param Http\ExecuteRequest $executeRequest
     * @param Request\LogEvent    $logEvent
     */
    public function __construct(
        string $host,
        string $username,
        string $password,
        Http\ExecuteRequest $executeRequest,
        Request\LogEvent $logEvent
    ) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->executeRequest = $executeRequest;
        $this->logEvent = $logEvent;
    }

    /**
     * @param string     $method
     * @param string     $uri
     * @param array|null $params
     *
     * @return array
     *
     * @throws Gateway\ApiException
     */
    public function execute(
        string $method,
        string $uri,
        array $params = []
    ) {
        $request = [
            'method' => $method,
            'uri' => $uri,
            'params' => $params
        ];

        try {
            $response = $this->executeRequest->execute(
                $method,
                sprintf('%s/services/2/%s', $this->host, $uri),
                [
                    'auth' => [
                        $this->username,
                        $this->password,
                    ],
                    'json' => $params
                ]
            );

            $response = $response->getBody();

            $this->logEvent->log(
                $request,
                $response
            );

            return $response;
        } catch (Http\Exception $e) {
            $response = $e->getResponse();

            $this->logEvent->log(
                $request,
                $response
            );

            throw new Gateway\ApiException($response);
        }
    }
}