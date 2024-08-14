<?php

declare(strict_types=1);

namespace Other\Entities;

/**
 * Employee class.
 * {@inheritdoc}
 */
class Employee extends Contractor
{
    /**
     * Type of all Employees of this class.
     */
    public const TYPE_CUSTOMER = ContractorType::EMPLOYEE;

    protected const FAKE_CONTRACTOR = [
        'id' => 2,
        'name' => 'EmployeeName',
        'email' => 'employee@example.com',
        'mobile' => '+7(222)222-22-22',
    ];
}
