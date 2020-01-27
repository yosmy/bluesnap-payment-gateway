<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Card;

use Yosmy\Mongo\ManageCollection;

/**
 * @di\service({
 *     private: true
 * })
 */
class ManageRefCollection extends ManageCollection
{
    /**
     * @di\arguments({
     *     uri: "%mongo_uri%",
     *     db:  "%mongo_db%"
     * })
     *
     * @param string $uri
     * @param string $db
     */
    public function __construct(
        string $uri,
        string $db
    ) {
        parent::__construct(
            $uri,
            $db,
            'yosmy_payment_gateway_bluesnap_card_refs',
            [
                'typeMap' => array(
                    'root' => Ref::class,
                ),
            ]
        );
    }
}
