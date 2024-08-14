<?php

declare(strict_types=1);

namespace Other\Entities;

/**
 * Client class.
 *
 * {@inheritdoc}
 *
 * @property Seller $seller client Seller.
 */
class Client extends Contractor
{
    /**
     * Type of all Clients of this class.
     */
    public const TYPE_CUSTOMER = ContractorType::CLIENT;

    protected const FAKE_CONTRACTOR = [
        'id' => 3,
        'name' => 'ClientName',
        'email' => 'client@example.com',
        'mobile' => '+7(333)333-33-33',
    ];

    protected function init(): void
    {
        parent::init();
        $this->seller = new Seller(1);
    }
}
