<?php

namespace Yosmy\Payment\Gateway\Bluesnap;

use Yosmy\Payment\Gateway;
use LogicException;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.add_card']
 * })
 */
class AddCard implements Gateway\AddCard
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @var Card\AddRef
     */
    private $addRef;

    /**
     * @var Gateway\ProcessApiException[]
     */
    private $processExceptionServices;

    /**
     * @di\arguments({
     *     processExceptionServices: '#yosmy.payment.gateway.bluesnap.add_card.exception_throwed',
     * })
     *
     * @param ExecuteRequest                $executeRequest
     * @param Card\AddRef                   $addRef
     * @param Gateway\ProcessApiException[] $processExceptionServices
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        Card\AddRef $addRef,
        array $processExceptionServices
    ) {
        $this->executeRequest = $executeRequest;
        $this->addRef = $addRef;
        $this->processExceptionServices = $processExceptionServices;
    }

    /**
     * {@inheritDoc}
     */
    public function add(
        string $customer,
        string $number,
        string $month,
        string $year,
        string $cvc
    ) {
        $last4 = substr($number, -4);

        $year = sprintf('20%s', $year);

        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_PUT,
                sprintf('vaulted-shoppers/%s', $customer),
                [
                    'paymentSources' => [
                        'creditCardInfo' => [
                            [
                                'creditCard' => [
                                    'cardNumber' => $number,
                                    'expirationYear' => $year,
                                    'expirationMonth' => $month,
                                    'securityCode' => $cvc
                                ]
                            ]
                        ]
                    ]
                ]
            );
        } catch (Gateway\ApiException $e) {
            foreach ($this->processExceptionServices as $service) {
                try {
                    $service->process($e);
                } catch (Gateway\FieldException|Gateway\IssuerException|Gateway\RiskException|Gateway\FraudException $e) {
                    throw $e;
                } catch (Gateway\FundsException $e) {
                    throw new LogicException(null, null, $e);
                }
            }

            throw new LogicException(
                null,
                null,
                $e
            );
        }

        $type = null;

        foreach ($response['paymentSources']['creditCardInfo'] as $card) {
            if (
                $last4 != $card['creditCard']['cardLastFourDigits']
                || $month != $card['creditCard']['expirationMonth']
                || $year != $card['creditCard']['expirationYear']
            ) {
                continue;
            }

            $type = $card['creditCard']['cardType'];
        }

        if (!$type) {
            throw new LogicException();
        }

        $id = $this->addRef->add(
            $number,
            $type
        );

        return new Gateway\Card(
            $id,
            $last4
        );
    }

    /**
     * {@inheritDoc}
     */
    public function identify() {
        return 'bluesnap';
    }
}