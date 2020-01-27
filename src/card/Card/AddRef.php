<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Card;

use Yosmy\Mongo\DuplicatedKeyException;
use LogicException;

/**
 * @di\service()
 */
class AddRef
{
    /**
     * @var ManageRefCollection
     */
    private $manageCollection;

    /**
     * @param ManageRefCollection $manageCollection
     */
    public function __construct(
        ManageRefCollection $manageCollection
    ) {
        $this->manageCollection = $manageCollection;
    }

    /**
     * @param string $number
     * @param string $type
     *
     * @return string The id
     */
    public function add(
        string $number,
        string $type
    )  {
        $id = uniqid();

        try {
            $this->manageCollection->insertOne([
                '_id' => $id,
                'number' => $number,
                'type' => $type
            ]);
        } catch (DuplicatedKeyException $e) {
            throw new LogicException(null, null, $e);
        }

        return $id;
    }
}
