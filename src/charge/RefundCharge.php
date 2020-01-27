<?php

namespace Yosmy\Payment\Gateway\Bluesnap;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.refund_charge']
 * })
 */
class RefundCharge implements Gateway\RefundCharge
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @param ExecuteRequest  $executeRequest
     */
    public function __construct(
        ExecuteRequest $executeRequest
    ) {
        $this->executeRequest = $executeRequest;
    }

    /**
     * {@inheritDoc}
     */
    public function refund(
        string $id
    ) {
        try {
            $this->executeRequest->execute(
                ExecuteRequest::METHOD_PUT,
                sprintf('transactions/%s/refund', $id),
                []
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function identify() {
        return 'bluesnap';
    }
}
