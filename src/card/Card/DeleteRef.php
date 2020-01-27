<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Card;

/**
 * @di\service()
 */
class DeleteRef
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
     */
    public function delete(
        string $id
    )  {
        $this->manageCollection->deleteOne([
            '_id' => $id,
        ]);
    }
}
