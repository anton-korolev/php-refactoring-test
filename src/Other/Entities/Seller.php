<?php

declare(strict_types=1);

namespace Other\Entities;

/**
 * Seller class.
 *
 * {@inheritdoc}
 */
class Seller extends Contractor
{
    /**
     * Type of all Sellers of this class.
     */
    public const TYPE_CUSTOMER = ContractorType::SELLER;

    protected const FAKE_CONTRACTOR = [
        'id' => 1,
        'name' => 'SellerName',
        'email' => 'seller@example.com',
        'mobile' => '+7(111)111-11-11',
    ];
}
