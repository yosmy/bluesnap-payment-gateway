<?php

namespace Yosmy\Payment\Gateway\Bluesnap;

use Yosmy\Payment\Gateway;
use LogicException;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.execute_charge']
 * })
 */
class ExecuteCharge implements Gateway\ExecuteCharge
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @var Card\PickRef
     */
    private $pickRef;

    /**
     * @var Gateway\ProcessApiException[]
     */
    private $processExceptionServices;

    /**
     * @di\arguments({
     *     processExceptionServices: '#yosmy.payment.gateway.bluesnap.execute_charge.exception_throwed',
     * })
     *
     * @param ExecuteRequest                $executeRequest
     * @param Card\PickRef                  $pickRef
     * @param Gateway\ProcessApiException[] $processExceptionServices
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        Card\PickRef $pickRef,
        ?array $processExceptionServices
    ) {
        $this->executeRequest = $executeRequest;
        $this->pickRef = $pickRef;
        $this->processExceptionServices = $processExceptionServices;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(
        string $customer,
        string $card,
        int $amount,
        string $description,
        string $statement
    ) {
        $amount = number_format($amount / 100, 2, '.', '');

        $ref = $this->pickRef->pick($card);

        $type = $ref->getType();
        $last4 = substr($ref->getNumber(), -4);

        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                'transactions',
                [
                    'amount' => $amount,
                    'currency' => 'USD',
                    'vaultedShopperId' => $customer,
                    'creditCard' => [
                        'cardLastFourDigits' => $last4,
                        'cardType' => $type,
                    ],
                    'cardTransactionType' => 'AUTH_ONLY'
                ]
            );

            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_PUT,
                'transactions',
                [
                    'transactionId' => $response['transactionId'],
                    'softDescriptor' => $statement,
                    'cardTransactionType' => 'CAPTURE'
                ]
            );

            return new Gateway\Charge(
                $response['transactionId'],
                time()
            );
        } catch (Gateway\ApiException $e) {
            foreach ($this->processExceptionServices as $service) {
                try {
                    $service->process($e);
                } catch (Gateway\FundsException|Gateway\IssuerException|Gateway\RiskException|Gateway\FraudException $e) {
                    throw $e;
                } catch (Gateway\FieldException $e) {
                    throw new LogicException(null, null, $e);
                }
            }

            throw new LogicException(null, null, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function identify() {
        return 'bluesnap';
    }
}
