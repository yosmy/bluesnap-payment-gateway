<?php

namespace Yosmy\Payment\Gateway\Bluesnap;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.delete_card']
 * })
 */
class DeleteCard implements Gateway\DeleteCard
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
     * @var Card\DeleteRef
     */
    private $deleteRef;

    /**
     * @param ExecuteRequest $executeRequest
     * @param Card\PickRef   $pickRef
     * @param Card\DeleteRef $deleteRef
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        Card\PickRef $pickRef,
        Card\DeleteRef $deleteRef
    ) {
        $this->executeRequest = $executeRequest;
        $this->pickRef = $pickRef;
        $this->deleteRef = $deleteRef;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(
        string $customer,
        string $card
    ) {
        $ref = $this->pickRef->pick($card);

        $type = $ref->getType();
        $last4 = substr($ref->getNumber(), -4);

        try {
            $this->executeRequest->execute(
                ExecuteRequest::METHOD_PUT,
                sprintf('vaulted-shoppers/%s', $customer),
                [
                    'paymentSources' => [
                        'creditCardInfo' => [
                            [
                                'creditCard' => [
                                    'cardType' => $type,
                                    'cardLastFourDigits' => $last4
                                ],
                                'status' => 'D'
                            ]
                        ]
                    ]
                ]
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }

        $this->deleteRef->delete($card);
    }

    /**
     * {@inheritDoc}
     */
    public function identify() {
        return 'bluesnap';
    }
}