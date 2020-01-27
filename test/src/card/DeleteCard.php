<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Test;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Bluesnap;
use LogicException;

/**
 * @di\service()
 */
class DeleteCard
{
    /**
     * @var Bluesnap\DeleteCard
     */
    private $deleteCard;

    /**
     * @param Bluesnap\DeleteCard $deleteCard
     */
    public function __construct(Bluesnap\DeleteCard $deleteCard)
    {
        $this->deleteCard = $deleteCard;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/bluesnap/delete-card"})
     *
     * @param string $customer
     * @param string $card
     */
    public function delete(
        string $customer,
        string $card
    ) {
        try {
            $this->deleteCard->delete(
                $customer,
                $card
            );
        } catch (Gateway\ApiException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}