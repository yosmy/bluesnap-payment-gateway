<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Card;

/**
 * @di\service()
 */
class PickRef
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
     * @param string $id
     *
     * @return Ref
     */
    public function pick(
        string $id
    )  {
        /** @var Ref $ref */
        $ref = $this->manageCollection->findOne([
            '_id' => $id
        ]);

        return $ref;
    }
}
