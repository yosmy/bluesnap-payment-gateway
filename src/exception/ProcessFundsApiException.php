<?php

namespace Yosmy\Payment\Gateway\Bluesnap;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.gateway.bluesnap.execute_charge.exception_throwed'
 *     ]
 * })
 */
class ProcessFundsApiException implements Gateway\ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(Gateway\ApiException $e)
    {
        // https://developer.bluesnap.com/More/Testing/Financial%20Response%20Codes

        // Low funds/Insufficient Balance
        if (
            $e->getResponse()['ResponseCode'] == '481'
            && in_array(
                $e->getResponse()['ISO'],
                ['58', '62', '65', '80']
            )
        ) {
            throw new Gateway\FundsException();
        }
    }
}