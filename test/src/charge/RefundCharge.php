<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Test;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Bluesnap;
use LogicException;

/**
 * @di\service()
 */
class RefundCharge
{
    /**
     * @var Bluesnap\RefundCharge
     */
    private $refundCharge;

    /**
     * @param Bluesnap\RefundCharge $refundCharge
     */
    public function __construct(Bluesnap\RefundCharge $refundCharge)
    {
        $this->refundCharge = $refundCharge;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/bluesnap/refund-charge"})
     *
     * @param string $id
     */
    public function delete(
        string $id
    ) {
        try {
            $this->refundCharge->refund(
                $id
            );
        } catch (Gateway\ApiException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}