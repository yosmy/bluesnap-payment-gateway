<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Test;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Bluesnap;
use LogicException;

/**
 * @di\service()
 */
class AddCustomer
{
    /**
     * @var Bluesnap\AddCustomer
     */
    private $addCustomer;

    /**
     * @param Bluesnap\AddCustomer $addCustomer
     */
    public function __construct(Bluesnap\AddCustomer $addCustomer)
    {
        $this->addCustomer = $addCustomer;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/bluesnap/add-customer"})
     *
     * @return Gateway\Customer
     */
    public function add() {
        try {
            return $this->addCustomer->add();
        } catch (Gateway\ApiException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}