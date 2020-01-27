<?php

namespace Yosmy\Payment\Gateway\Bluesnap;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.gateway.bluesnap.add_card.exception_throwed'
 *     ]
 * })
 */
class ProcessFieldApiException implements Gateway\ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(Gateway\ApiException $e)
    {
        foreach ($e->getResponse()['message'] as $message) {
            if (
                isset($message['code'])
                && $message['code'] == '10001'
                && isset($message['invalidProperty'])
                && isset($message['invalidProperty']['name'])
            ) {
                switch ($message['invalidProperty']['name']) {
                    case 'expirationMonth':
                        $field = 'month';

                        break;
                    default:
                        return;
                }

                throw new Gateway\FieldException($field);
            }
        }
    }
}