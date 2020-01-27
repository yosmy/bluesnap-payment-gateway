<?php

namespace Yosmy\Payment\Gateway\Bluesnap\Card;

use MongoDB\Model\BSONDocument;

class Ref extends BSONDocument
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->offsetGet('id');
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->offsetGet('number');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->offsetGet('type');
    }

    /**
     * {@inheritdoc}
     */
    public function bsonUnserialize(array $data)
    {
        $data['id'] = $data['_id'];
        unset($data['_id']);

        parent::bsonUnserialize($data);
    }
}